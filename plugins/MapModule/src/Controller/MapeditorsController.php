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

namespace MapModule\Controller;

use App\Lib\Exceptions\MissingDbBackendException;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\WidgetsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Exception;
use InvalidArgumentException;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\MapConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\System\FileUploadSize;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Filter\MapFilter;
use itnovum\openITCOCKPIT\Maps\MapForAngular;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapgadget;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapicon;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapitem;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapline;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapsummaryitem;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Maptext;
use MapModule\Model\Table\MapgadgetsTable;
use MapModule\Model\Table\MapiconsTable;
use MapModule\Model\Table\MapitemsTable;
use MapModule\Model\Table\MaplinesTable;
use MapModule\Model\Table\MapsTable;
use MapModule\Model\Table\MapsummaryitemsTable;
use MapModule\Model\Table\MaptextsTable;
use MapModule\Model\Table\MapUploadsTable;
use RuntimeException;
use Statusengine\PerfdataParser;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


/**
 * Class MapeditorsController
 * @package MapModule\Controller
 */
class MapeditorsController extends AppController {

    /**
     * @param null $id
     */
    public function view($id = null) {
        if (!$this->isApiRequest() && $id === null) {
            //Only ship html template
            return;
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $id = (int)$id;

        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException();
        }
        $map = $MapsTable->get($id, [
            'contain' => [
                'Containers',
                'Mapgadgets',
                'Mapicons',
                'Mapitems',
                'Maplines',
                'Maptexts',
                'Mapsummaryitems'
            ]
        ])->toArray();
        $containerIdsToCheck = Hash::extract($map, 'containers.{n}.id');
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $MapForAngular = new MapForAngular($map);
        $map = $MapForAngular->toArray();

        $acl = [
            'hosts'         => [
                'browser' => isset($this->PERMISSIONS['hosts']['browser']),
                'index'   => isset($this->PERMISSIONS['hosts']['index'])
            ],
            'services'      => [
                'browser' => isset($this->PERMISSIONS['services']['browser']),
                'index'   => isset($this->PERMISSIONS['services']['index'])

            ],
            'hostgroups'    => [
                'extended' => isset($this->PERMISSIONS['hostgroups']['extended'])
            ],
            'servicegroups' => [
                'extended' => isset($this->PERMISSIONS['servicegroups']['extended'])
            ]
        ];

        $this->set('map', $map);
        $this->set('ACL', $acl);

        $this->viewBuilder()->setOption('serialize', ['map', 'ACL']);
    }

    /**
     * @throws Exception
     */
    public function mapitem() {
        if (!$this->isApiRequest()) {
            return;
        }
        $objectId = (int)$this->request->getQuery('objectId');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        $mapId = (int)$this->request->getQuery('mapId');
        if ($mapId <= 0) {
            throw new RuntimeException('Invalid map id');
        }

        try {
            $data = $this->_mapitem(
                $objectId,
                $mapId,
                $this->request->getQuery('type'),
                $this->request->getQuery('includeServiceOutput')
            );
        } catch (Exception $e) {
            throw $e;
        }

        $this->set('type', $this->request->getQuery('type'));
        $this->set('data', $data['data']);
        $this->set('allowView', $data['allowView']);
        $this->viewBuilder()->setOption('serialize', ['type', 'allowView', 'data']);
    }

    /**
     * @throws Exception
     */
    public function mapitemMulti() {
        if (!$this->isApiRequest()) {
            return;
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $items = $this->request->getData('items', []);

        $mapitems = [];
        foreach ($items as $item) {
            if (isset($item['mapId']) && isset($item['objectId']) && isset($item['type']) && isset($item['uuid'])) {
                try {
                    $data = $this->_mapitem(
                        $item['objectId'],
                        $item['mapId'],
                        $item['type']
                    );

                    $mapitems[$item['uuid']] = $data;
                } catch (Exception $e) {
                    throw $e;
                }
            }

        }
        $this->set('mapitems', $mapitems);
        $this->viewBuilder()->setOption('serialize', ['mapitems']);
    }

    /**
     * @param $objectId
     * @param $mapId
     * @param $type
     * @param string $includeServiceOutput
     * @return array
     * @throws MissingDbBackendException
     */
    private function _mapitem($objectId, $mapId, $type, $includeServiceOutput = 'true') {
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        if ($mapId <= 0) {
            throw new RuntimeException('Invalid map id');
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        $properties =[];

        switch ($type) {
            case 'host':
                $host = $HostsTable->getHostById($objectId);

                if (!empty($host)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getHostInformation(
                        $ServicesTable,
                        $HoststatusTable,
                        $ServicestatusTable,
                        $host
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'service':
                $includeServiceOutput = $includeServiceOutput === 'true';
                $service = $ServicesTable->getServiceByIdWithHostAndServicetemplate($objectId);

                if (!empty($service)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId($service->getContainerIds(), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getServiceInformation($ServicestatusTable, $service, $includeServiceOutput);
                    break;
                }
                $allowView = false;
                break;

            case 'hostgroup':
                try {
                    /** @var HostgroupsTable $HostgroupsTable */
                    $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
                    $hostgroup = $HostgroupsTable->getHostsByHostgroupForMaps($objectId, []);
                    $hostgroup['hosts'] = array_merge(
                        $hostgroup['hosts'],
                        Hash::extract($hostgroup, 'hosttemplates.{n}.hosts.{n}')
                    );
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($hostgroup, 'hosts.{n}.hosts_to_containers_sharing.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getHostgroupInformation(
                        $ServicesTable,
                        $hostgroup,
                        $HoststatusTable,
                        $ServicestatusTable
                    );
                    break;
                } catch (Exception $e) {
                    $allowView = false;
                }
                break;
            case 'servicegroup':
                /** @var ServicegroupsTable $ServicegroupsTable */
                $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
                try {
                    $servicegroup = $ServicegroupsTable->getServicegroupsByServicegroupForMaps($objectId, []);
                    $servicegroup['services'] = array_merge(
                        $servicegroup['services'],
                        Hash::extract($servicegroup, 'servicetemplates.{n}.services.{n}')
                    );
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(array_unique(Hash::extract($servicegroup, 'services.{n}.host.hosts_to_containers_sharing.{n}.id')), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getServicegroupInformation(
                        $ServicesTable,
                        $ServicestatusTable,
                        $servicegroup
                    );
                    break;
                } catch (RecordNotFoundException $exception) {
                    $allowView = false;
                }
                break;
            case 'map':
                $map = $MapsTable->getMapsForMaps($objectId, $mapId, false);
                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'containers.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    /** @var MapitemsTable $MapitemsTable */
                    $MapitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapitems');

                    //fetch all dependent map items after permissions check
                    $mapItemToResolve = $MapitemsTable->getMapitemsForMaps($map['id'], $mapId);
                    if (empty($mapItemToResolve)) {
                        //Empty map
                        $allowView = true;
                        $properties = $MapsTable->getMapInformation(
                            $HoststatusTable,
                            $ServicestatusTable,
                            $map,
                            [],
                            []
                        );
                    }
                    if (!empty($mapItemToResolve)) {
                        /** @var HostgroupsTable $HostgroupsTable */
                        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
                        /** @var ServicegroupsTable $ServicegroupsTable */
                        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

                        $allVisibleItems = $MapitemsTable->allVisibleMapItems($mapId, $this->hasRootPrivileges ? [] : $this->MY_RIGHTS, false);
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.object_id',
                            '{n}.object_id',
                            '{n}.map_id'
                        );
                        if (isset($mapIdGroupByMapId[$objectId])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $objectId);
                        }
                        $dependentMapsIds[] = $objectId;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElements = $MapsTable->getAllDependentMapsElements(
                            $dependentMapsIds,
                            $HostgroupsTable,
                            $ServicegroupsTable,
                            $this->hasRootPrivileges ? [] : $this->MY_RIGHTS
                        );
                        $hosts = [];
                        $services = [];
                        if (!empty($allDependentMapElements['hostIds'])) {
                            $hosts = $HostsTable->getHostsWithServicesByIdsForMapeditor($allDependentMapElements['hostIds'], false);

                            if (!empty($hosts)) {
                                if ($this->hasRootPrivileges === false) {
                                    if (!$this->allowedByContainerId(Hash::extract($hosts, '{n}.hosts_to_containers_sharing.{n}.id'), false)) {
                                        $allowView = false;
                                        break;
                                    }
                                }
                                foreach ($hosts as $host) {
                                    foreach ($host['services'] as $serviceData) {
                                        $services[$serviceData['id']] = [
                                            'Service' => $serviceData
                                        ];
                                    }
                                }
                            }
                        }
                        if (!empty($allDependentMapElements['serviceIds'])) {
                            $dependentServices = $ServicesTable->getServicesByIdsForMapeditor($allDependentMapElements['serviceIds']);

                            if (!empty($dependentServices)) {
                                if ($this->hasRootPrivileges === false) {
                                    if (!$this->allowedByContainerId(Hash::extract($dependentServices, '{n}.HostsToContainers.container_id'), false)) {
                                        $allowView = false;
                                        break;
                                    }
                                }
                                foreach ($dependentServices as $service) {
                                    $hosts[$service['Hosts']['id']] = ['Host' => $service['Hosts']];
                                    $services[$service['id']] = [
                                        'Service' => $service
                                    ];
                                }
                            }
                        }
                        $allowView = true;
                        $properties = $MapsTable->getMapInformation(
                            $HoststatusTable,
                            $ServicestatusTable,
                            $map,
                            $hosts,
                            $services
                        );
                    }
                    break;
                }
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

        return [
            'type'      => $type,
            'data'      => $properties,
            'allowView' => $allowView
        ];
    }

    /**
     * @param $maps
     * @param $parentMapId
     * @return array
     */
    public function getDependendMaps($maps, $parentMapId) {
        $allRelatedMapIdsOfParent = [];

        $childMapIds = $maps[$parentMapId];
        foreach ($childMapIds as $childMapId) {
            $allRelatedMapIdsOfParent[] = (int)$childMapId;
            //Is the children map used as parent map in an other relation?
            if (isset($maps[$childMapId]) && !in_array($childMapId, $allRelatedMapIdsOfParent, true)) { //in_array to avoid endless loop
                //Rec
                $allRelatedMapIdsOfParent = array_merge(
                    $allRelatedMapIdsOfParent,
                    $this->getDependendMaps($maps, $childMapId)
                );
            }
        }

        return $allRelatedMapIdsOfParent;
    }

    public function mapline() {
        //Only ship template
        return;
    }

    public function mapicon() {
        //Only ship template
        return;
    }

    public function maptext() {
        //Only ship template
        return;
    }

    public function perfdatatext() {
        //Only ship template
        return;
    }

    public function serviceOutput() {
        //Only ship template
        return;
    }

    /**
     * @throws MissingDbBackendException
     */
    public function mapsummaryitem() {
        if (!$this->isApiRequest()) {
            return;
        }
        $properties = [];
        $objectId = (int)$this->request->getQuery('objectId');
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        $mapId = (int)$this->request->getQuery('mapId');
        if ($mapId <= 0) {
            throw new RuntimeException('Invalid map id');
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        switch ($this->request->getQuery('type')) {
            case 'host':
                /** @var HostsTable $HostsTable */
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

                $host = $HostsTable->getHostsWithServicesByIdsForMapeditor($objectId);

                if (!empty($host) && isset($host[0])) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($host[0]->toArray(), 'hosts_to_containers_sharing.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getHostInformationForSummaryIcon(
                        $HoststatusTable,
                        $ServicestatusTable,
                        $host[0]->toArray()
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'service':
                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');

                $service = $ServicesTable->getServiceById($objectId)->toArray();

                if (!empty($service)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($service, 'host.hosts_to_containers_sharing.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getServiceInformationForSummaryIcon(
                        $HoststatusTable,
                        $ServicestatusTable,
                        $service
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'hostgroup':
                /** @var HostgroupsTable $HostgroupsTable */
                $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

                $hostgroup = $HostgroupsTable->getHostgroupByIdForMapeditor($objectId);

                if (!empty($hostgroup) && isset($hostgroup[0])) {
                    if ($this->hasRootPrivileges !== false) {
                        if (!$this->allowedByContainerId(Hash::extract($hostgroup[0]->toArray(), 'hosts.{n}.hosts_to_containers_sharing.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getHostgroupInformationForSummaryIcon(
                        $HoststatusTable,
                        $ServicestatusTable,
                        $hostgroup[0]->toArray()
                    );
                    break;
                }
                $allowView = false;
                break;

            case 'servicegroup':
                /** @var ServicegroupsTable $ServicegroupsTable */
                $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

                $servicegroup = $ServicegroupsTable->getServicegroupByIdForMapeditor($objectId);
                if (!empty($servicegroup)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($servicegroup, 'services.{n}.host.hosts_to_containers_sharing.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }
                    $allowView = true;
                    $properties = $MapsTable->getServicegroupInformationForSummaryIcon(
                        $HoststatusTable,
                        $ServicestatusTable,
                        $servicegroup
                    );
                    break;
                }
                $allowView = false;
                break;
            case 'map':
                /** @var MapsummaryitemsTable $MapsummaryitemsTable */
                $MapsummaryitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapsummaryitems');
                /** @var MapitemsTable $MapitemsTable */
                $MapitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapitems');
                /** @var HostgroupsTable $HostgroupsTable */
                $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
                /** @var ServicegroupsTable $ServicegroupsTable */
                $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
                /** @var HostsTable $HostsTable */
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');

                $map = $MapsTable->getMapsForMapsummaryitems($objectId, $mapId, false);
                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'containers.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }

                    //fetch all dependent map items after permissions check
                    $mapSummaryItemToResolve = $MapsummaryitemsTable->getMapsummaryitemsForMaps($map['id'], $mapId);
                    if (!empty($mapSummaryItemToResolve)) {
                        $allVisibleItems = $MapsummaryitemsTable->allVisibleMapsummaryitems($mapId, $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.object_id',
                            '{n}.object_id',
                            '{n}.map_id'
                        );
                        if (isset($mapIdGroupByMapId[$objectId])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $objectId);
                        }
                        $dependentMapsIds[] = $objectId;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElementsFromSubMaps['mapsummaryitem'] = $MapsTable->getAllDependentMapsElements(
                            $dependentMapsIds,
                            $HostgroupsTable,
                            $ServicegroupsTable,
                            $this->hasRootPrivileges ? [] : $this->MY_RIGHTS
                        );
                    }

                    //fetch all dependent map items after permissions check SIMPLE STATE ITEMS (TYPE MAP)
                    $mapItemToResolve = $MapitemsTable->getMapitemsForMaps($objectId, $mapId);

                    if (!empty($mapItemToResolve)) {
                        $allVisibleItems = $MapitemsTable->allVisibleMapItems($mapId, $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.object_id',
                            '{n}.object_id',
                            '{n}.map_id'
                        );

                        if (isset($mapIdGroupByMapId[$objectId])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $objectId);
                        }
                        $dependentMapsIds[] = $objectId;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElementsFromSubMaps['mapitem'] = $MapsTable->getAllDependentMapsElements(
                            $dependentMapsIds,
                            $HostgroupsTable,
                            $ServicegroupsTable,
                            $this->hasRootPrivileges ? [] : $this->MY_RIGHTS
                        );
                    }

                    //simple item (host/hostgroup/service/servicegroup)
                    $allDependentMapElementsFromSubMaps['item'] = $MapsTable->getAllDependentMapsElements(
                        $map['id'],
                        $HostgroupsTable,
                        $ServicegroupsTable
                    );


                    $hostIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.hostIds.{n}');
                    $serviceIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.serviceIds.{n}');

                    $hosts = [];
                    $services = [];
                    if (!empty($hostIds)) {
                        $hostsById = $HostsTable->getHostsWithServicesByIdsForMapeditor($hostIds);

                        if (!empty($hostsById)) {
                            if ($this->hasRootPrivileges === false) {
                                if (!$this->allowedByContainerId(Hash::extract($hostsById, '{n}.hosts_to_containers_sharing.{n}.id'), false)) {
                                    $allowView = false;
                                    break;
                                }
                            }
                            foreach ($hostsById as $host) {
                                $hosts[$host['id']] = $host;
                                foreach ($host['services'] as $serviceData) {
                                    $services[$serviceData['id']] = [
                                        'Service' => $serviceData
                                    ];
                                }
                            }
                        }
                    }

                    if (!empty($serviceIds)) {
                        $serviceIds = array_unique($serviceIds);


                        $dependentServices = $ServicesTable->getServicesByIdsForMapeditor($serviceIds);

                        if (!empty($dependentServices)) {
                            if ($this->hasRootPrivileges === false) {
                                if (!$this->allowedByContainerId(Hash::extract($dependentServices, '{n}.HostsToContainers.container_id'), false)) {
                                    $allowView = false;
                                    break;
                                }
                            }
                            foreach ($dependentServices as $service) {
                                $hosts[$service['Hosts']['id']] = ['Host' => $service['Hosts']];
                                $services[$service['id']] = [
                                    'Service' => $service
                                ];
                            }
                        }
                    }
                    $allowView = true;

                    $properties = $MapsTable->getMapInformationForSummaryIcon(
                        $HoststatusTable,
                        $ServicestatusTable,
                        $map,
                        $hosts,
                        $services
                    );
                    break;
                }
                $allowView = false;
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

        $this->set('type', $this->request->getQuery('type'));
        $this->set('data', $properties);
        $this->set('allowView', $allowView);
        $this->viewBuilder()->setOption('serialize', ['type', 'allowView', 'data']);
    }

    public function graph() {
        if (!$this->isApiRequest()) {
            return;
        }

        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $serviceId = (int)$this->request->getQuery('serviceId');
        $service = $ServicesTable->getServicesByIdsForMapsumary($serviceId);

        if (empty($service) || !isset($service[0])) {
            throw new NotFoundException('Service not found!');
        }
        $service = $service[0];
        if ($this->hasRootPrivileges === false) {
            if (!$this->allowedByContainerId(Hash::extract($service, 'Hosts.HostsToContainers.container_id'), false)) {
                $this->set('allowView', false);
                $this->viewBuilder()->setOption('serialize', ['allowView']);
            }
        }
        $Service = new Service($service);
        $Host = new Host($service['Hosts']);
        $this->set('host', $Host->toArray());
        $this->set('service', $Service->toArray());
        $this->set('allowView', true);
        $this->viewBuilder()->setOption('serialize', ['allowView', 'host', 'service']);
    }

    public function tacho() {
        //Only ship template
        return;
    }

    public function cylinder() {
        //Only ship template
        return;
    }

    public function trafficlight() {
        //Only ship template
        return;
    }

    public function temperature() {
        //Only ship template
        return;
    }

    /**
     * @throws MissingDbBackendException
     */
    public function mapsummary() {
        if (!$this->isApiRequest()) {
            return;
        }

        $objectId = (int)$this->request->getQuery('objectId');
        $summaryStateItem = $this->request->getQuery('summary') === 'true';
        if ($objectId <= 0) {
            throw new RuntimeException('Invalid object id');
        }
        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        /** @var ServicegroupsTable $ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        switch ($this->request->getQuery('type')) {
            case 'host':
                /** @var HostsTable $HostsTable */
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

                $host = $HostsTable->get($objectId, [
                    'contain' => [
                        'HostsToContainersSharing'
                    ],
                    'fields'  => [
                        'Hosts.id',
                        'Hosts.uuid',
                        'Hosts.name',
                        'Hosts.description',
                        'Hosts.disabled'
                    ],
                ])->toArray();

                if (!empty($host)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($host, 'hosts_to_containers_sharing.{n}.id'), false)) {
                            $this->render403();
                            return;
                        }
                    }
                    $summary = $MapsTable->getHostSummary(
                        $ServicesTable,
                        $HoststatusTable,
                        $ServicestatusTable,
                        $host,
                        $UserTime
                    );
                    $this->set('type', 'host');
                    $this->set('summary', $summary);
                    $this->viewBuilder()->setOption('serialize', ['host', 'summary']);
                    return;
                }

                throw new NotFoundException('Host not found!');
                break;
            case 'service':
                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $service = $ServicesTable->get($objectId, [
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

                if (!empty($service)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($service, 'host.hosts_to_containers_sharing.{n}.id'), false)) {
                            $this->render403();
                            return;
                        }
                    }
                    $summary = $MapsTable->getServiceSummary(
                        $ServicesTable,
                        $HoststatusTable,
                        $ServicestatusTable,
                        $service,
                        $UserTime
                    );
                    $this->set('type', 'service');
                    $this->set('summary', $summary);
                    $this->viewBuilder()->setOption('serialize', ['service', 'summary']);
                    return;
                }

                throw new NotFoundException('Service not found!');
                break;
            case 'hostgroup':
                try {
                    $MY_RIGHTS = [];
                    if (!$this->hasRootPrivileges) {
                        $MY_RIGHTS = $this->MY_RIGHTS;
                    }
                    $hostgroup = $HostgroupsTable->getHostsByHostgroupForMaps($objectId, $MY_RIGHTS);
                    $hostgroup['hosts'] = array_merge(
                        $hostgroup['hosts'],
                        Hash::extract($hostgroup, 'hosttemplates.{n}.hosts.{n}')
                    );

                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(
                            array_unique(Hash::extract($hostgroup, 'hosts.{n}.hosts_to_containers_sharing.{n}.id'), false))) {
                            $this->render403();
                            return;
                        }
                    }
                    $summary = $MapsTable->getHostgroupSummary(
                        $HostsTable,
                        $ServicesTable,
                        $HoststatusTable,
                        $ServicestatusTable,
                        $hostgroup
                    );
                    $this->set('type', 'hostgroup');
                    $this->set('summary', $summary);
                    $this->viewBuilder()->setOption('serialize', ['hostgroup', 'summary']);
                    return;
                } catch (Exception $e) {
                    throw new NotFoundException('Host group not found!');
                }
                break;
            case 'servicegroup':
                $servicegroup = $ServicegroupsTable->getServicegroupByIdForMapeditor($objectId, $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);
                $servicegroup['services'] = array_merge(
                    $servicegroup['services'],
                    Hash::extract($servicegroup, 'servicetemplates.{n}.services.{n}')
                );
                if (!empty($servicegroup)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(array_unique(Hash::extract($servicegroup, 'services.{n}.host.hosts_to_containers_sharing.{n}.id')), false)) {
                            $this->render403();
                            return;
                        }
                    }

                    $summary = $MapsTable->getServicegroupSummary(
                        $ServicesTable,
                        $ServicestatusTable,
                        $servicegroup
                    );
                    $this->set('type', 'servicegroup');
                    $this->set('summary', $summary);
                    $this->viewBuilder()->setOption('serialize', ['servicegroup', 'summary']);
                    return;
                }

                throw new NotFoundException('Service group not found!');
                break;
            case 'map':
                /** @var MapsummaryitemsTable $MapsummaryitemsTable */
                $MapsummaryitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapsummaryitems');
                /** @var MapitemsTable $MapitemsTable */
                $MapitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapitems');

                if ($summaryStateItem) {
                    $map = $MapsTable->getMapsummaryitemForMapsummary($objectId);
                    $mapId = $map['Mapsummaryitems']['map_id'];
                } else {
                    $map = $MapsTable->getMapitemForMapsummary($objectId);
                    $mapId = $map['Mapitems']['map_id'];
                }

                if (!empty($map)) {
                    if ($this->hasRootPrivileges === false) {
                        if (!$this->allowedByContainerId(Hash::extract($map, 'containers.{n}.id'), false)) {
                            $allowView = false;
                            break;
                        }
                    }

                    //fetch all dependent map items after permissions check
                    $mapSummaryItemToResolve = $MapsummaryitemsTable->getMapsummaryitemsForMaps($map['id'], $mapId);
                    if (!empty($mapSummaryItemToResolve)) {
                        $allVisibleItems = $MapsummaryitemsTable->allVisibleMapsummaryitems($mapId, $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.Mapsummaryitem.object_id',
                            '{n}.Mapsummaryitem.object_id',
                            '{n}.Mapsummaryitem.map_id'
                        );
                        if (isset($mapIdGroupByMapId[$objectId])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $objectId);
                        }
                        $dependentMapsIds[] = $objectId;

                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElementsFromSubMaps['mapsummaryitem'] = $MapsTable->getAllDependentMapsElements(
                            $dependentMapsIds,
                            $HostgroupsTable,
                            $ServicegroupsTable
                        );
                    }

                    //fetch all dependent map items after permissions check SIMPLE STATE ITEMS (TYPE MAP)
                    $mapItemToResolve = $MapitemsTable->getMapitemsForMaps($objectId, $mapId);
                    if (!empty($mapItemToResolve)) {
                        $allVisibleItems = $MapitemsTable->allVisibleMapItems($mapId, $this->hasRootPrivileges ? [] : $this->MY_RIGHTS);

                        $mapItemIdToResolve = $mapItemToResolve['object_id'];
                        $mapIdGroupByMapId = Hash::combine(
                            $allVisibleItems,
                            '{n}.object_id',
                            '{n}.object_id',
                            '{n}.map_id'
                        );
                        if (isset($mapIdGroupByMapId[$mapItemIdToResolve])) {
                            $dependentMapsIds = $this->getDependendMaps($mapIdGroupByMapId, $mapItemIdToResolve);
                        }
                        $dependentMapsIds[] = $objectId;
                        // resolve all Elements (host and/or services of dependent map)
                        $allDependentMapElementsFromSubMaps['mapitem'] = $MapsTable->getAllDependentMapsElements(
                            $dependentMapsIds,
                            $HostgroupsTable,
                            $ServicegroupsTable
                        );
                    }

                    //simple item (host/hostgroup/service/servicegroup)
                    $allDependentMapElementsFromSubMaps['item'] = $MapsTable->getAllDependentMapsElements(
                        $map['id'],
                        $HostgroupsTable,
                        $ServicegroupsTable
                    );

                    $hostIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.hostIds.{n}');
                    $serviceIds = Hash::extract($allDependentMapElementsFromSubMaps, '{s}.serviceIds.{n}');

                    $hosts = [];
                    $services = [];

                    if (!empty($hostIds)) {
                        $hosts = $HostsTable->getHostsWithServicesByIdsForMapsumary($hostIds, false);

                        if (!empty($hosts)) {
                            if ($this->hasRootPrivileges === false) {
                                if (!$this->allowedByContainerId(Hash::extract($hosts, '{n}.hosts_to_containers_sharing.{n}.id'), false)) {
                                    break;
                                }
                            }
                            foreach ($hosts as $host) {
                                foreach ($host['services'] as $serviceData) {
                                    $services[$serviceData['id']] = [
                                        'Service' => $serviceData,
                                        'Host'    => [
                                            'name' => $host['name']
                                        ]
                                    ];
                                }
                            }
                        }
                    }

                    if (!empty($serviceIds)) {
                        $servicesToMerge = $ServicesTable->getServicesByIdsForMapsumary($serviceIds);

                        if (!empty($servicesToMerge)) {
                            if ($this->hasRootPrivileges === false) {
                                if (!$this->allowedByContainerId(Hash::extract($servicesToMerge, '{n}.HostsToContainers.container_id'), false)) {
                                    break;
                                }
                            }
                            foreach ($servicesToMerge as $service) {
                                $hosts[$service['Hosts']['id']] = $service['Hosts'];
                                $services[$service['id']]['Service'] = $service;
                            }
                        }
                    }
                    $summary = $MapsTable->getMapSummary(
                        $HostsTable,
                        $HoststatusTable,
                        $ServicesTable,
                        $ServicestatusTable,
                        $map,
                        $hosts,
                        $services,
                        $UserTime,
                        $summaryStateItem
                    );

                    $this->set('type', 'map');
                    $this->set('summary', $summary);
                    $this->viewBuilder()->setOption('serialize', ['map', 'summary']);
                    return;
                }
                throw new NotFoundException('Map not found!');
                break;
            default:
                throw new RuntimeException('Unknown map item type');
                break;
        }

    }

    /**
     * @param null $id
     */
    public function edit($id = null) {
        if (!$this->isApiRequest() && $id === null) {
            //Only ship template

            /** @var MapUploadsTable $MapUploadsTable */
            $MapUploadsTable = TableRegistry::getTableLocator()->get('MapModule.MapUploads');

            $gadgetPreviews = [
                'RRDGraph'      => 'graph_gadget.png',
                'Tacho'         => 'tacho_gadget.png',
                'TrafficLight'  => 'trafficlight_gadget.png',
                'Cylinder'      => 'cylinder_gadget.png',
                'Text'          => 'perfdata_gadget.png',
                'Temperature'   => 'temperature_gadget.png',
                'ServiceOutput' => 'serviceoutput_gadget.png'
            ];
            $this->set('gadgetPreviews', $gadgetPreviews);
            $this->set('requiredIcons', $MapUploadsTable->getIconsNames());
            return;
        }

        $FileUploadSize = new FileUploadSize();
        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $id = (int)$id;
        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException();
        }
        $map = $MapsTable->get($id, [
            'contain' => [
                'Containers',
                'Mapgadgets',
                'Mapicons',
                'Mapitems',
                'Maplines',
                'Maptexts',
                'Mapsummaryitems'
            ]
        ])->toArray();
        $MapForAngular = new MapForAngular($map);
        $map = $MapForAngular->toArray();
        $config = $MapsTable->getMapeditorSettings($map['Map']['json_data']);

        $this->set('map', $map);
        $this->set('config', $config);
        $this->set('maxUploadLimit', $FileUploadSize->toArray());
        $this->set('max_z_index', $MapForAngular->getMaxZIndex());
        $this->set('layers', $MapForAngular->getLayers());
        $this->viewBuilder()->setOption('serialize', ['map', 'maxUploadLimit', 'max_z_index', 'layers', 'config']);
    }

    public function backgroundImages() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!is_dir(APP . '../' . 'plugins' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds')) {
            mkdir(APP . '../' . 'plugins' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds');
        }

        if (!is_dir(APP . '../' . 'plugins' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds' . DS . 'thumb')) {
            mkdir(APP . '../' . 'plugins' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds' . DS . 'thumb');
        }

        $finder = new Finder();
        $finder->files()->in(APP . '../' . 'plugins' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds')->exclude('thumb');

        $backgrounds = [];
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $backgrounds[] = [
                'image'     => $file->getFilename(),
                'path'      => sprintf('/map_module/img/backgrounds/%s', $file->getFilename()),
                'thumbnail' => sprintf('/map_module/img/backgrounds/thumb/thumb_%s', $file->getFilename()),
            ];
        }

        $this->set('backgrounds', $backgrounds);
        $this->viewBuilder()->setOption('serialize', ['backgrounds']);
    }

    public function getIconsets() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapUploadsTable $MapUploadsTable */
        $MapUploadsTable = TableRegistry::getTableLocator()->get('MapModule.MapUploads');

        $iconsets = $MapUploadsTable->getIconSets();

        $this->set('iconsets', $iconsets);
        $this->viewBuilder()->setOption('serialize', ['iconsets']);
    }

    public function loadMapsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');
        $excluded = $this->request->getQuery('excluded');

        $MapFilter = new MapFilter($this->request);

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $MapConditions = new MapConditions($MapFilter->indexFilter());
        $MapConditions->setContainerIds($this->MY_RIGHTS);

        $maps = Api::makeItJavaScriptAble(
            $MapsTable->getMapsForAngular($MapConditions, $selected, $excluded)
        );

        $this->set('maps', $maps);
        $this->viewBuilder()->setOption('serialize', ['maps']);
    }

    public function saveItem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapitemsTable $MapItemsTable */
        $MapItemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapitems');

        //Save new possition after drag and drop
        if ($this->request->getData('action') === 'dragstop') {
            $id = $this->request->getData('Mapitem.id');

            $itemEntity = $MapItemsTable->get($id);

            $item = $itemEntity->toArray();
            $item['x'] = (int)$this->request->getData('Mapitem.x');
            $item['y'] = (int)$this->request->getData('Mapitem.y');
            if ($this->request->getData('Mapitem.show_label') !== null) {
                $item['show_label'] = (int)$this->request->getData('Mapitem.show_label');
            }
            $itemEntity = $MapItemsTable->patchEntity($itemEntity, $item);
            $MapItemsTable->save($itemEntity);
            if (!$itemEntity->hasErrors()) {
                $mapitem = new Mapitem($itemEntity->toArray());

                $this->set('Mapitem', [
                    'Mapitem' => $mapitem->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapitem']);
                return;
            }
            $this->serializeCake4Id($itemEntity);
            return;
        }


        //Create new item or update existing one
        if (empty($this->request->getData('Mapitem.id')) || $this->request->getData('Mapitem.id') === null) {
            $itemEntity = $MapItemsTable->newEmptyEntity();
        } else {
            $itemEntity = $MapItemsTable->get($this->request->getData('Mapitem.id'));
        }

        $item = $this->request->getData();
        $item['Mapitem']['show_label'] = (int)$this->request->getData('Mapitem.show_label');

        $MapItemsTable->patchEntity($itemEntity, $item['Mapitem']);
        $MapItemsTable->save($itemEntity);
        if ($itemEntity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $itemEntity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $mapitem = new Mapitem($itemEntity->toArray());

        $this->set('Mapitem', [
            'Mapitem' => $mapitem->toArray()
        ]);
        $this->viewBuilder()->setOption('serialize', ['Mapitem']);
    }

    public function deleteItem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('Mapitem.id');

        /** @var MapitemsTable $MapitemsTable */
        $MapitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapitems');

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapitem.id needs to be numeric');
        }

        if (!$MapitemsTable->existsById($id)) {
            throw new NotFoundException();
        }

        if ($MapitemsTable->delete($MapitemsTable->get($id))) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function saveLine() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MaplinesTable $MaplinesTable */
        $MaplinesTable = TableRegistry::getTableLocator()->get('MapModule.Maplines');

        //Save new possition after drag and drop
        if ($this->request->getData('action') === 'dragstop') {
            $lineEntity = $MaplinesTable->get($this->request->getData('Mapline.id'));

            $line = [];
            $line['startX'] = (int)$this->request->getData('Mapline.startX');
            $line['startY'] = (int)$this->request->getData('Mapline.startY');
            $line['endX'] = (int)$this->request->getData('Mapline.endX');
            $line['endY'] = (int)$this->request->getData('Mapline.endY');
            $lineEntity = $MaplinesTable->patchEntity($lineEntity, $line);
            $MaplinesTable->save($lineEntity);
            if (!$lineEntity->hasErrors()) {
                $Mapline = new Mapline($lineEntity->toArray());

                $this->set('Mapline', [
                    'Mapline' => $Mapline->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapline']);
                return;
            }
            $this->serializeCake4Id($lineEntity);
            return;
        }

        //Create new item or update existing one
        if (empty($this->request->getData('Mapline.id'))) {
            //$this->Mapline->create();
            $lineEntity = $MaplinesTable->newEmptyEntity();
        } else {
            $lineEntity = $MaplinesTable->get($this->request->getData('Mapline.id'));
        }
        $data = $this->request->getData();
        $data['Mapline']['show_label'] = (int)$data['Mapline']['show_label'];
        $lineEntity = $MaplinesTable->patchEntity($lineEntity, $data['Mapline']);

        $MaplinesTable->save($lineEntity);
        if ($lineEntity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $lineEntity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $mapline = new Mapline($lineEntity->toArray());

        $this->set('Mapline', [
            'Mapline' => $mapline->toArray()
        ]);
        $this->viewBuilder()->setOption('serialize', ['Mapline']);
    }

    public function deleteLine() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MaplinesTable $MaplinesTable */
        $MaplinesTable = TableRegistry::getTableLocator()->get('MapModule.Maplines');

        $id = $this->request->getData('Mapline.id');

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapline.id needs to be numeric');
        }
        if (!$MaplinesTable->existsById($id)) {
            throw new NotFoundException();
        }

        if ($MaplinesTable->delete($MaplinesTable->get($id))) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function saveGadget() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapgadgetsTable $MapgadgetsTable */
        $MapgadgetsTable = TableRegistry::getTableLocator()->get('MapModule.Mapgadgets');

        //Save new possition after drag and drop
        if ($this->request->getData('action') === 'dragstop') {
            $gadgetEntity = $MapgadgetsTable->get($this->request->getData('Mapgadget.id'));

            $gadget['x'] = (int)$this->request->getData('Mapgadget.x');
            $gadget['y'] = (int)$this->request->getData('Mapgadget.y');

            $gadgetEntity = $MapgadgetsTable->patchEntity($gadgetEntity, $gadget);
            $MapgadgetsTable->save($gadgetEntity);
            if (!$gadgetEntity->hasErrors()) {
                $Mapgadget = new Mapgadget($gadgetEntity->toArray());

                $this->set('Mapgadget', [
                    'Mapgadget' => $Mapgadget->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapgadget']);
                return;
            }
            $this->serializeCake4Id($gadgetEntity);
            return;
        }

        //Save new gadget size
        if ($this->request->getData('action') === 'resizestop') {
            $gadgetEntity = $MapgadgetsTable->get($this->request->getData('Mapgadget.id'));

            $gadget['size_x'] = (int)$this->request->getData('Mapgadget.size_x');
            $gadget['size_y'] = (int)$this->request->getData('Mapgadget.size_y');

            $gadgetEntity = $MapgadgetsTable->patchEntity($gadgetEntity, $gadget);
            $MapgadgetsTable->save($gadgetEntity);
            if (!$gadgetEntity->hasErrors()) {
                $Mapgadget = new Mapgadget($gadgetEntity->toArray());

                $this->set('Mapgadget', [
                    'Mapgadget' => $Mapgadget->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapgadget']);
                return;
            }
            $this->serializeCake4Id($gadgetEntity);
            return;
        }

        if (empty($this->request->getData('Mapgadget.id'))) {
            $gadgetEntity = $MapgadgetsTable->newEmptyEntity();
        } else {
            $gadgetEntity = $MapgadgetsTable->get($this->request->getData('Mapgadget.id'));
        }

        $gadget = $this->request->getData('Mapgadget');
        $gadget['show_label'] = (int)$this->request->getData('Mapgadget.show_label');
        $gadget['size_x'] = (int)$this->request->getData('Mapgadget.size_x');
        $gadget['size_y'] = (int)$this->request->getData('Mapgadget.size_y');
        $gadget['transparent_background'] = (int)$this->request->getData('Mapgadget.transparent_background');
        $gadgetEntity = $MapgadgetsTable->patchEntity($gadgetEntity, $gadget);

        $MapgadgetsTable->save($gadgetEntity);


        if ($gadgetEntity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $gadgetEntity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $Mapgadget = new Mapgadget($gadgetEntity->toArray());

        $this->set('Mapgadget', [
            'Mapgadget' => $Mapgadget->toArray()
        ]);
        $this->viewBuilder()->setOption('serialize', ['Mapgadget']);
    }

    public function deleteGadget() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapgadgetsTable $MapgadgetsTable */
        $MapgadgetsTable = TableRegistry::getTableLocator()->get('MapModule.Mapgadgets');

        $id = $this->request->getData('Mapgadget.id');

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapgadget.id needs to be numeric');
        }
        if (!$MapgadgetsTable->existsById($id)) {
            throw new NotFoundException();
        }

        if ($MapgadgetsTable->delete($MapgadgetsTable->get($id))) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    /**
     * @param $serviceId
     * @throws MissingDbBackendException
     */
    public function getPerformanceDataMetrics($serviceId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        if (!$ServicesTable->existsById($serviceId)) {
            throw new NotFoundException();
        }

        $service = $ServicesTable->get($serviceId)->toArray();

        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->perfdata();
        $servicestatus = $ServicestatusTable->byUuid($service['uuid'], $ServicestatusFields);

        if (!empty($servicestatus)) {
            $PerfdataParser = new PerfdataParser($servicestatus['Servicestatus']['perfdata']);
            $this->set('perfdata', $PerfdataParser->parse());
            $this->viewBuilder()->setOption('serialize', ['perfdata']);
            return;
        }
        $this->set('perfdata', []);
        $this->viewBuilder()->setOption('serialize', ['perfdata']);
    }

    public function saveText() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MaptextsTable $MaptextsTable */
        $MaptextsTable = TableRegistry::getTableLocator()->get('MapModule.Maptexts');

        //Save new possition after drag and drop
        if ($this->request->getData('action') === 'dragstop') {
            $maptextEntity = $MaptextsTable->get($this->request->getData('Maptext.id'));
            $maptext = $maptextEntity->toArray();

            $maptext['x'] = (int)$this->request->getData('Maptext.x');
            $maptext['y'] = (int)$this->request->getData('Maptext.y');
            $maptext['font_size'] = 11;

            $MaptextsTable->patchEntity($maptextEntity, $maptext);
            $MaptextsTable->save($maptextEntity);
            if (!$maptextEntity->hasErrors()) {
                $Maptext = new Maptext($maptext);

                $this->set('Maptext', [
                    'Maptext' => $Maptext->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Maptext']);
                return;
            }
            $this->serializeCake4Id($maptextEntity);
            return;
        }

        //Create new text or update existing one
        if (empty($this->request->getData('Maptext.id'))) {
            $maptextEntity = $MaptextsTable->newEmptyEntity();
        } else {
            $maptextEntity = $MaptextsTable->get((int)$this->request->getData('Maptext.id'));
        }

        $text = $this->request->getData('Maptext');

        $text['x'] = (int)$text['x'];
        $text['y'] = (int)$text['y'];
        $text['font_size'] = 11;

        $MaptextsTable->patchEntity($maptextEntity, $text);
        $MaptextsTable->save($maptextEntity);
        if (!$maptextEntity->hasErrors()) {
            $text['id'] = (int)$maptextEntity->id;
            $Maptext = new Maptext($text);
            $this->set('Maptext', [
                'Maptext' => $Maptext->toArray()
            ]);
            $this->viewBuilder()->setOption('serialize', ['Maptext']);
            return;
        }
        $this->serializeCake4Id($maptextEntity);
    }

    public function deleteText() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MaptextsTable $MaptextsTable */
        $MaptextsTable = TableRegistry::getTableLocator()->get('MapModule.Maptexts');

        $id = $this->request->getData('Maptext.id');
        if (!$MaptextsTable->existsById($id)) {
            throw new NotFoundException();
        }

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Maptext.id needs to be numeric');
        }

        if ($MaptextsTable->delete($MaptextsTable->get($id))) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function saveIcon() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapiconsTable $MapiconsTable */
        $MapiconsTable = TableRegistry::getTableLocator()->get('MapModule.Mapicons');

        //Save new possition after drag and drop
        if ($this->request->getData('action') === 'dragstop') {
            $mapiconEntity = $MapiconsTable->get($this->request->getData('Mapicon.id'));

            $mapicon['x'] = (int)$this->request->getData('Mapicon.x');
            $mapicon['y'] = (int)$this->request->getData('Mapicon.y');

            $mapiconEntity = $MapiconsTable->patchEntity($mapiconEntity, $mapicon);
            $MapiconsTable->save($mapiconEntity);
            if (!$mapiconEntity->hasErrors()) {
                $Mapicon = new Mapicon($mapiconEntity->toArray());

                $this->set('Mapicon', [
                    'Mapicon' => $Mapicon->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapicon']);
                return;
            }
            $this->serializeCake4Id($mapiconEntity);
            return;
        }

        //Create new icon or update existing one
        if (empty($this->request->getData('Mapicon.id'))) {
            $mapiconEntity = $MapiconsTable->newEmptyEntity();
        } else {
            $mapiconEntity = $MapiconsTable->get((int)$this->request->getData('Mapicon.id'));
        }

        $icon = $this->request->getData();

        $icon['Mapicon']['x'] = (int)$this->request->getData('Mapicon.x');
        $icon['Mapicon']['y'] = (int)$this->request->getData('Mapicon.y');

        $mapiconEntity = $MapiconsTable->patchEntity($mapiconEntity, $icon['Mapicon']);
        $MapiconsTable->save($mapiconEntity);
        if (!$mapiconEntity->hasErrors()) {
            $Mapicon = new Mapicon($mapiconEntity->toArray());
            $this->set('Mapicon', [
                'Mapicon' => $Mapicon->toArray()
            ]);
            $this->viewBuilder()->setOption('serialize', ['Mapicon']);
            return;
        }
        $this->serializeCake4Id($mapiconEntity);
    }

    public function deleteIcon() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('Mapicon.id');

        /** @var MapiconsTable $MapiconsTable */
        $MapiconsTable = TableRegistry::getTableLocator()->get('MapModule.Mapicons');

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapicon.id needs to be numeric');
        }
        if (!$MapiconsTable->existsById($id)) {
            throw new NotFoundException();
        }

        if ($MapiconsTable->delete($MapiconsTable->get($id))) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function saveBackground() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $id = $this->request->getData('Map.id');
        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException();
        }

        $map = $MapsTable->get($id, [
            'contain' => [
                'Containers',
                'Mapgadgets',
                'Mapicons',
                'Mapitems',
                'Maplines',
                'Maptexts',
                'Mapsummaryitems'
            ]
        ]);

        $map->background = $this->request->getData('Map.background');


        $MapsTable->save($map);
        if ($map->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $map->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }
        $this->set('Map', [
            'Map' => $map
        ]);

        $this->viewBuilder()->setOption('serialize', ['Map']);
    }

    public function getIcons() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapUploadsTable $MapUploadsTable */
        $MapUploadsTable = TableRegistry::getTableLocator()->get('MapModule.MapUploads');

        $icons = $MapUploadsTable->getIcons();

        $this->set('icons', $icons);
        $this->viewBuilder()->setOption('serialize', ['icons']);
    }

    public function saveSummaryitem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapsummaryitemsTable $MapsummaryitemsTable */
        $MapsummaryitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapsummaryitems');

        //Save new possition after drag and drop
        if ($this->request->getData('action') === 'dragstop') {
            $mapsummaryitemEntity = $MapsummaryitemsTable->get($this->request->getData('Mapsummaryitem.id'));

            $mapsummaryitem['Mapsummaryitem']['x'] = (int)$this->request->getData('Mapsummaryitem.x');
            $mapsummaryitem['Mapsummaryitem']['y'] = (int)$this->request->getData('Mapsummaryitem.y');
            $mapsummaryitemEntity = $MapsummaryitemsTable->patchEntity($mapsummaryitemEntity, $mapsummaryitem['Mapsummaryitem']);
            $MapsummaryitemsTable->save($mapsummaryitemEntity);
            if (!$mapsummaryitemEntity->hasErrors()) {
                $Mapsummaryitem = new Mapsummaryitem($mapsummaryitemEntity->toArray());

                $this->set('Mapsummaryitem', [
                    'Mapsummaryitem' => $Mapsummaryitem->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapsummaryitem']);
                return;
            }
            $this->serializeCake4Id($mapsummaryitemEntity);
            return;
        }

        //Save new Mapsummaryitem size
        if ($this->request->getData('action') === 'resizestop') {
            $mapsummaryitemEntity = $MapsummaryitemsTable->get($this->request->getData('Mapsummaryitem.id'));

            $mapsummaryitem['Mapsummaryitem']['size_x'] = (int)$this->request->getData('Mapsummaryitem.size_x');
            $mapsummaryitem['Mapsummaryitem']['size_y'] = (int)$this->request->getData('Mapsummaryitem.size_y');
            $mapsummaryitemEntity = $MapsummaryitemsTable->patchEntity($mapsummaryitemEntity, $mapsummaryitem['Mapsummaryitem']);
            $MapsummaryitemsTable->save($mapsummaryitemEntity);
            if (!$mapsummaryitemEntity->hasErrors()) {
                $Mapsummaryitem = new Mapsummaryitem($mapsummaryitemEntity->toArray());

                $this->set('Mapsummaryitem', [
                    'Mapsummaryitem' => $Mapsummaryitem->toArray()
                ]);

                $this->viewBuilder()->setOption('serialize', ['Mapsummaryitem']);
                return;
            }
            $this->serializeCake4Id($mapsummaryitemEntity);
            return;
        }

        //Create new Mapsummaryitem or update existing one
        if (empty($this->request->getData('Mapsummaryitem.id'))) {
            $mapsummaryitemEntity = $MapsummaryitemsTable->newEmptyEntity();
        } else {
            $mapsummaryitemEntity = $MapsummaryitemsTable->get($this->request->getData('Mapsummaryitem.id'));
        }

        $mapsummaryitem = $this->request->getData();
        $mapsummaryitem['Mapsummaryitem']['show_label'] = (int)$this->request->getData('Mapsummaryitem.show_label');
        $mapsummaryitem['Mapsummaryitem']['size_x'] = (int)$this->request->getData('Mapsummaryitem.size_x');
        $mapsummaryitem['Mapsummaryitem']['size_y'] = (int)$this->request->getData('Mapsummaryitem.size_y');
        $mapsummaryitemEntity = $MapsummaryitemsTable->patchEntity($mapsummaryitemEntity, $mapsummaryitem['Mapsummaryitem']);
        $MapsummaryitemsTable->save($mapsummaryitemEntity);

        if ($mapsummaryitemEntity->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set('error', $mapsummaryitemEntity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return;
        }

        $mapSummaryitem = new Mapsummaryitem($mapsummaryitemEntity->toArray());

        $this->set('Mapsummaryitem', [
            'Mapsummaryitem' => $mapSummaryitem->toArray()
        ]);
        $this->viewBuilder()->setOption('serialize', ['Mapsummaryitem']);
    }

    public function deleteSummaryitem() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getData('Mapsummaryitem.id');

        /** @var MapsummaryitemsTable $MapsummaryitemsTable */
        $MapsummaryitemsTable = TableRegistry::getTableLocator()->get('MapModule.Mapsummaryitems');

        if (!is_numeric($id)) {
            throw new InvalidArgumentException('Mapsummaryitem.id needs to be numeric');
        }
        if (!$MapsummaryitemsTable->existsById($id)) {
            throw new NotFoundException();
        }

        if ($MapsummaryitemsTable->delete($MapsummaryitemsTable->get($id))) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    /**
     * @param $id
     */
    public function mapDetails($id) {
        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $id = (int)$id;
        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException();
        }
        $map = $MapsTable->get($id, [
            //'recursive'  => -1,
            'contain' => [
                'Containers'
            ]
        ])->toArray();

        $containerIdsToCheck = Hash::extract($map, 'containers.{n}.id');
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $MapForAngular = new MapForAngular($map);
        $map = $MapForAngular->toArray();

        $this->set('map', $map);
        $this->viewBuilder()->setOption('serialize', ['map']);
    }

    public function viewDirective() {
        //Ship template of Mapeditors view directive.
        //It is a directive be able to also use the maps as an widget
        return;
    }

    public function mapWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');
        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->getQuery('widgetId');
            if (!$WidgetsTable->existsById($widgetId)) {
                throw new RuntimeException('Invalid widget id');
            }

            $widgetEntity = $WidgetsTable->get($widgetId);
            $widget = $widgetEntity->toArray();
            $config = [
                'map_id' => null
            ];
            if ($widget['json_data'] !== null && $widget['json_data'] !== '') {
                $config = json_decode($widget['json_data'], true);
                if (!isset($config['map_id'])) {
                    $config['map_id'] = null;
                }
            }

            //Check map permissions
            if ($config['map_id'] !== null) {
                $map = $MapsTable->getMapForMapwidget($config['map_id']);
                if (!empty($map) && isset($map[0])) {
                    $containerIdsToCheck = Hash::extract($map[0]->toArray(), 'containers.{n}._joinData.container_id');
                    if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
                        $config['map_id'] = null;
                    }
                }
            }

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }


        if ($this->request->is('post')) {
            $mapId = (int)$this->request->getData('map_id');
            if ($mapId === 0) {
                $mapId = null;
            }

            $config = [
                'map_id' => $mapId
            ];
            $widgetId = (int)$this->request->getData('Widget.id');

            if (!$WidgetsTable->existsById($widgetId)) {
                throw new RuntimeException('Invalid widget id');
            }

            $widgetEntity = $WidgetsTable->get($widgetId);
            $widget['json_data'] = json_encode($config);

            $widgetEntity = $WidgetsTable->patchEntity($widgetEntity, $widget);
            $WidgetsTable->save($widgetEntity);
            if (!$widgetEntity->hasErrors()) {
                $this->set('config', $config);
                $this->viewBuilder()->setOption('serialize', ['config']);
                return;
            }

            $this->serializeCake4Id($widgetEntity);
            return;
        }
        throw new MethodNotAllowedException();
    }

    public function saveMapeditorSettings() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var MapsTable $MapsTable */
        $MapsTable = TableRegistry::getTableLocator()->get('MapModule.Maps');

        $id = $this->request->getData('Map.id');
        if (!$MapsTable->existsById($id)) {
            throw new NotFoundException();
        }

        $config['Mapeditor'] = $this->request->getData('Mapeditor', []);
        if (!empty($config)) {
            $MapsTable->updateAll([
                'json_data' => json_encode($config)
            ], [
                'id' => $id
            ]);
        }
        $this->set('success', true);
        $this->set('message', __('Mapeditor settings successfully updated'));
        $this->viewBuilder()->setOption('serialize', ['success']);
        return;
    }
}
