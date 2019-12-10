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

namespace App\Controller;

use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use App\Lib\Traits\PluginManagerTableTrait;
use App\Model\Entity\Changelog;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\CommandargumentsTable;
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\DocumentationsTable;
use App\Model\Table\HostcommandargumentvaluesTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use DistributeModule\Model\Table\SatellitesTable;
use itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\HostComparisonForSave;
use itnovum\openITCOCKPIT\Core\CustomMacroReplacer;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostControllerRequest;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\HostNotificationConditions;
use itnovum\openITCOCKPIT\Core\HostSharingPermissions;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\HosttemplateMerger;
use itnovum\openITCOCKPIT\Core\Merger\HostMergerForView;
use itnovum\openITCOCKPIT\Core\ModuleManager;
use itnovum\openITCOCKPIT\Core\Permissions\HostContainersPermissions;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\Timeline\AcknowledgementSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\DowntimeSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\Groups;
use itnovum\openITCOCKPIT\Core\Timeline\NotificationSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\StatehistorySerializer;
use itnovum\openITCOCKPIT\Core\Timeline\TimeRangeSerializer;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\Downtime;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Hosttemplate;
use itnovum\openITCOCKPIT\Core\Views\ServiceStateSummary;
use itnovum\openITCOCKPIT\Core\Views\StatehistoryHost;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;


/**
 * @property Host $Host
 * @property Hosttemplatecommandargumentvalue $Hosttemplatecommandargumentvalue
 * @property Hostcommandargumentvalue $Hostcommandargumentvalue
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property DeletedHost $DeletedHost
 * @property DeletedService $DeletedService
 * @property Container $Container
 * @property Parenthost $Parenthost
 * @property Hosttemplate $Hosttemplate
 * @property Hostgroup $Hostgroup
 * @property Timeperiod $Timeperiod
 * @property DowntimeHost $DowntimeHost
 * @property BbcodeComponent $Bbcode
 * @property StatehistoryHost $StatehistoryHost
 * @property DateRange $DateRange
 * @property NotificationHost $NotificationHost
 * @property Service $Service
 *
 * @property AppPaginatorComponent $Paginator
 */
class HostsController extends AppController {

    use PluginManagerTableTrait;


    /**
     * @deprecated
     */
    public function index() {
        $User = new User($this->getUser());

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $Systemsettings->getMasterInstanceName();

        $satellites = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            /** @var $SatellitesTable SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

            $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
            $satellites[0] = $masterInstanceName;
        }

        if (!$this->isApiRequest()) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            $this->set('satellites', $satellites);
            //Only ship HTML template
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $HostFilter = new HostFilter($this->request);

        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        $User = new User($this->getUser());
        if ($HostControllerRequest->isRequestFromBrowser() === false) {
            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($this->MY_RIGHTS);
        }

        if ($HostControllerRequest->isRequestFromBrowser() === true) {
            $browserContainerIds = $HostControllerRequest->getBrowserContainerIdsByRequest();
            foreach ($browserContainerIds as $containerIdToCheck) {
                if (!in_array($containerIdToCheck, $this->MY_RIGHTS)) {
                    $this->render403();
                    return;
                }
            }

            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($browserContainerIds);

            if ($User->isRecursiveBrowserEnabled()) {
                //get recursive container ids
                $containerIdToResolve = $browserContainerIds;

                $children = $ContainersTable->getChildren($containerIdToResolve[0]);
                $containerIds = Hash::extract($children, '{n}.id');
                $recursiveContainerIds = [];
                foreach ($containerIds as $containerId) {
                    if (in_array($containerId, $this->MY_RIGHTS)) {
                        $recursiveContainerIds[] = $containerId;
                    }
                }
                $HostCondition->setContainerIds(array_merge($HostCondition->getContainerIds(), $recursiveContainerIds));
            }
        }

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $HostFilter->getPage());

        if ($this->DbBackend->isNdoUtils()) {
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            /** @var $ServicestatusTable ServicestatusTableInterface */
            $ServicestatusTable = TableRegistry::getTableLocator()->get('Statusengine2Module.Servicestatus');
            $hosts = $HostsTable->getHostsIndex($HostFilter, $HostCondition, $PaginateOMat);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
            //$query = $this->Hoststatus->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            //$modelName = 'Hoststatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
            //$query = $this->Host->getHostIndexQueryStatusengine3($HostCondition, $HostFilter->indexFilter());
            //$this->Host->virtualFieldsForIndex();
            //$modelName = 'Host';
        }


        $all_hosts = [];
        $UserTime = new UserTime($User->getTimezone(), $User->getDateformat());
        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->currentState();

        /** @var ServicesTable $ServiceTable */
        $ServiceTable = TableRegistry::getTableLocator()->get('Services');

        foreach ($hosts as $host) {
            $serviceUuids = $ServiceTable->find('list', [
                'valueField' => 'uuid'
            ])
                ->where([
                    'Services.host_id' => $host['Host']['id']
                ])
                ->toList();

            $servicestatus = $ServicestatusTable->byUuid($serviceUuids, $ServicestatusFields);
            $ServicestatusObjects = Servicestatus::fromServicestatusByUuid($servicestatus);
            $serviceStateSummary = ServiceStateSummary::getServiceStateSummary($ServicestatusObjects, false);

            $serviceStateSummary['state'] = array_combine(
                [
                    __('ok'),
                    __('warning'),
                    __('critical'),
                    __('unknown')
                ],
                $serviceStateSummary['state']
            );

            $Host = new Host($host['Host']);
            $Hoststatus = new Hoststatus($host['Host']['Hoststatus'], $UserTime);

            $hostSharingPermissions = new HostSharingPermissions(
                $Host->getContainerId(), $this->hasRootPrivileges, $Host->getContainerIds(), $this->MY_RIGHTS
            );
            $allowSharing = $hostSharingPermissions->allowSharing();

            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $satellites[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'                 => $Host->toArray(),
                'Hoststatus'           => $Hoststatus->toArray(),
                'ServicestatusSummary' => $serviceStateSummary
            ];
            $tmpRecord['Host']['allow_sharing'] = $allowSharing;
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;
            $tmpRecord['Host']['allow_edit'] = $allowEdit;

            $all_hosts[] = $tmpRecord;
        }

        $this->set('all_hosts', $all_hosts);

        $toJson = ['all_hosts', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hosts', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    public function icon() {
        //Only ship HTML Template
        return;
    }

    public function hostservicelist() {
        //Only ship HTML Template
        return;
    }

    /**
     * @param null $id
     * @throws MissingDbBackendException
     */
    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var \App\Model\Entity\Host $host */
        $host = $HostsTable->getHostById($id);

        if (!$this->allowedByContainerId($host->getContainerIds())) {
            $this->render403();
            return;
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->wildcard();

        if ($this->DbBackend->isNdoUtils()) {
            /** @var $HoststatusTable HoststatusTableInterface */
            $HoststatusTable = TableRegistry::getTableLocator()->get('Statusengine2Module.Hoststatus');
            $hoststatus = $HoststatusTable->byUuid($host->get('uuid'), $HoststatusFields);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if (empty($hoststatus)) {
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $Hoststatus = new Hoststatus($hoststatus['Hoststatus']);

        $this->set('host', $host);
        $this->set('hoststatus', $Hoststatus->toArray());
        $this->viewBuilder()->setOption('serialize', ['host', 'hoststatus']);
    }

    public function byUuid($uuid) {
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        try {
            $host = $HostsTable->getHostByUuid($uuid);

            if (!$this->allowedByContainerId($host->getContainerIds())) {
                $this->render403();
                return;
            }
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('Host not found');
        }

        $this->set('host', $host);
        $this->viewBuilder()->setOption('serialize', ['host']);
    }

    public function notMonitored() {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $masterInstanceName = $Systemsettings->getMasterInstanceName();
        $SatelliteNames = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            $MY_RIGHTS = [];
            if ($this->hasRootPrivileges === false) {
                $MY_RIGHTS = $this->MY_RIGHTS;
            }
            /** @var $SatellitesTable \DistributeModule\Model\Table\SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');
            $SatelliteNames = $SatellitesTable->getSatellitesAsList($MY_RIGHTS);
            $SatelliteNames[0] = $masterInstanceName;
        }

        $User = new User($this->getUser());
        if (!$this->isApiRequest()) {
            //Only ship HTML template

            $this->set('username', $User->getFullName());
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }

        $HostFilter = new HostFilter($this->request);
        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        $HostCondition->setIncludeDisabled(false);
        $HostCondition->setContainerIds($this->MY_RIGHTS);


        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $HostFilter->getPage());


        if ($this->DbBackend->isNdoUtils()) {
            $hosts = $HostsTable->getHostsNotMonitored($HostFilter, $HostCondition, $PaginateOMat);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
            //$this->loadModel('CrateModule.CrateHost');
            //$query = $this->CrateHost->getHostNotMonitoredQuery($HostCondition, $HostFilter->notMonitoredFilter());
            //$this->CrateHost->alias = 'Host';
            //$modelName = 'CrateHost';
        }

        if ($this->DbBackend->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
            //$query = $this->Host->getHostNotMonitoredQuery($HostCondition, $HostFilter->notMonitoredFilter());
            //$modelName = 'Host';
        }


        $all_hosts = [];
        foreach ($hosts as $host) {
            $Host = new Host($host);

            $hostSharingPermissions = new HostSharingPermissions(
                $Host->getContainerId(), $this->hasRootPrivileges, $Host->getContainerIds(), $this->MY_RIGHTS
            );
            $allowSharing = $hostSharingPermissions->allowSharing();

            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $SatelliteNames[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'       => $Host->toArray(),
                'Hoststatus' => [
                    'isInMonitoring' => false,
                    'currentState'   => -1
                ]
            ];
            $tmpRecord['Host']['allow_sharing'] = $allowSharing;
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $all_hosts[] = $tmpRecord;
        }


        $this->set('all_hosts', $all_hosts);
        $toJson = ['all_hosts', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hosts', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            if (!$this->request->getData('Host.container_id') || !$this->request->getData('Host.hosttemplate_id')) {
                $errors = [];
                $this->response = $this->response->withStatus(400);
                if (!$this->request->getData('Host.container_id')) {

                    $errors['container_id'] = [
                        'empty' => __('This field cannot be left empty')
                    ];
                }
                if (!$this->request->getData('Host.hosttemplate_id')) {
                    $errors['hosttemplate_id'] = [
                        'empty' => __('This field cannot be left empty')
                    ];
                }
                $this->set('error', $errors);
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }


            /** @var $HosttemplatesTable HosttemplatesTable */
            $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            $hosttemplateId = $this->request->getData('Host.hosttemplate_id');
            if (!$HosttemplatesTable->existsById($hosttemplateId)) {
                throw new NotFoundException(__('Invalid host template'));
            }

            $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($hosttemplateId);
            $HostComparisonForSave = new HostComparisonForSave($this->request->getData(), $hosttemplate);
            $hostData = $HostComparisonForSave->getDataForSaveForAllFields();
            $hostData['uuid'] = UUID::v4();

            //Add required fields for validation
            $hostData['hosttemplate_flap_detection_enabled'] = $hosttemplate['Hosttemplate']['flap_detection_enabled'];
            $hostData['hosttemplate_flap_detection_on_up'] = $hosttemplate['Hosttemplate']['flap_detection_on_up'];
            $hostData['hosttemplate_flap_detection_on_down'] = $hosttemplate['Hosttemplate']['flap_detection_on_down'];
            $hostData['hosttemplate_flap_detection_on_unreachable'] = $hosttemplate['Hosttemplate']['flap_detection_on_unreachable'];

            $host = $HostsTable->newEntity($hostData);

            $HostsTable->save($host);
            if ($host->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $host->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                $User = new User($this->getUser());
                $requestData = $this->request->getData();

                $extDataForChangelog = $HostsTable->resolveDataForChangelog($requestData);
                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'add',
                    'hosts',
                    $host->get('id'),
                    OBJECT_HOST,
                    $host->get('container_id'),
                    $User->getId(),
                    $host->get('name'),
                    array_merge($requestData, $extDataForChangelog)
                );

                if ($changelog_data) {
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }


                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($host); // REST API ID serialization
                    return;
                }
            }
            $this->set('host', $host);
            $this->viewBuilder()->setOption('serialize', ['host']);
        }
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Host not found'));
        }

        $host = $HostsTable->getHostForEdit($id);
        $hostForChangelog = $host;

        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return host information
            $commands = $CommandsTable->getCommandByTypeAsList(HOSTCHECK_COMMAND);
            $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($host['Host']['hosttemplate_id']);

            $HostMergerForView = new HostMergerForView($host, $hosttemplate);
            $mergedHost = $HostMergerForView->getDataForView();

            $HostContainersPermissions = new HostContainersPermissions(
                $host['Host']['container_id'],
                $host['Host']['hosts_to_containers_sharing']['_ids'],
                $this->getWriteContainers(),
                $this->hasRootPrivileges
            );

            $isHostOnlyEditableDueToHostSharing = $HostContainersPermissions->isHostOnlyEditableDueToHostSharing();

            $fakeDisplayContainers = [];
            if ($isHostOnlyEditableDueToHostSharing === true) {
                //The user only see this host via host sharing
                //We need to "fake" a primary container because the user has no permissions to the real
                //primary container
                $fakeDisplayContainers = $ContainersTable->getFakePrimaryContainerForHostEditDisplay(
                    $host['Host']['container_id'],
                    $host['Host']['hosts_to_containers_sharing']['_ids'],
                    $this->MY_RIGHTS
                );
            }

            $this->set('commands', Api::makeItJavaScriptAble($commands));
            $this->set('host', $mergedHost);
            $this->set('hosttemplate', $hosttemplate);
            $this->set('isPrimaryContainerChangeable', $HostContainersPermissions->isPrimaryContainerChangeable());
            $this->set('allowSharing', $HostContainersPermissions->allowSharing($this->MY_RIGHTS, $host['Host']['host_type']));
            $this->set('isHostOnlyEditableDueToHostSharing', $isHostOnlyEditableDueToHostSharing);
            $this->set('fakeDisplayContainers', Api::makeItJavaScriptAble($fakeDisplayContainers));
            $this->set('areContactsInheritedFromHosttemplate', $HostMergerForView->areContactsInheritedFromHosttemplate());

            $this->viewBuilder()->setOption('serialize', [
                'host',
                'commands',
                'hosttemplate',
                'isPrimaryContainerChangeable',
                'allowSharing',
                'isHostOnlyEditableDueToHostSharing',
                'fakeDisplayContainers',
                'areContactsInheritedFromHosttemplate'
            ]);
            return;
        }


        if ($this->request->is('post')) {
            $hosttemplateId = $this->request->getData('Host.hosttemplate_id');
            if ($hosttemplateId === null) {
                throw new Exception('Host.hosttemplate_id needs to set.');
            }
            if (!$HosttemplatesTable->existsById($hosttemplateId)) {
                throw new NotFoundException(__('Invalid host template'));
            }
            $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($hosttemplateId);

            $HostContainersPermissions = new HostContainersPermissions(
                $host['Host']['container_id'],
                $host['Host']['hosts_to_containers_sharing']['_ids'],
                $this->getWriteContainers(),
                $this->hasRootPrivileges
            );
            $requestData = $this->request->getData();

            if ($HostContainersPermissions->isPrimaryContainerChangeable() === false) {
                //Overwrite post data. User is not permitted to set a new primary container id!
                $requestData['Host']['container_id'] = $host['Host']['container_id'];
            }

            if ($HostContainersPermissions->allowSharing($this->MY_RIGHTS, $host['Host']['host_type']) === false) {
                //Overwrite post data. User is not permitted to set new shared containers
                $requestData['Host']['hosts_to_containers_sharing']['_ids'] = $host['Host']['hosts_to_containers_sharing']['_ids'];
            }

            $HostComparisonForSave = new HostComparisonForSave($requestData, $hosttemplate);

            $dataForSave = $HostComparisonForSave->getDataForSaveForAllFields();
            //Add required fields for validation
            $dataForSave['hosttemplate_flap_detection_enabled'] = $hosttemplate['Hosttemplate']['flap_detection_enabled'];
            $dataForSave['hosttemplate_flap_detection_on_up'] = $hosttemplate['Hosttemplate']['flap_detection_on_up'];
            $dataForSave['hosttemplate_flap_detection_on_down'] = $hosttemplate['Hosttemplate']['flap_detection_on_down'];
            $dataForSave['hosttemplate_flap_detection_on_unreachable'] = $hosttemplate['Hosttemplate']['flap_detection_on_unreachable'];

            //Update contact data
            $User = new User($this->getUser());
            $hostEntity = $HostsTable->get($id);
            $hostEntity = $HostsTable->patchEntity($hostEntity, $dataForSave);
            $HostsTable->save($hostEntity);
            if ($hostEntity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $hostEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'edit',
                    'hosts',
                    $hostEntity->get('id'),
                    OBJECT_HOST,
                    $hostEntity->get('container_id'),
                    $User->getId(),
                    $hostEntity->get('name'),
                    array_merge($HostsTable->resolveDataForChangelog($requestData), $requestData),
                    array_merge($HostsTable->resolveDataForChangelog($hostForChangelog), $hostForChangelog)
                );
                if ($changelog_data) {
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }

                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($hostEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('host', $hostEntity);
            $this->viewBuilder()->setOption('serialize', ['host']);
        }
    }

    public function sharing($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $User = new User($this->getUser());

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Host not found'));
        }

        $host = $HostsTable->getHostSharing($id);
        $hostForChangelog = $host;

        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        $HostContainersPermissions = new HostContainersPermissions(
            $host['Host']['container_id'],
            $host['Host']['hosts_to_containers_sharing']['_ids'],
            $this->getWriteContainers(),
            $this->hasRootPrivileges
        );

        $allowSharing = $HostContainersPermissions->allowSharing($this->MY_RIGHTS, $host['Host']['host_type']);

        if (!$allowSharing) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return host information

            //In sharing view the user can not change the primary container
            //This is because may be contacts or timeperiods etc are not available in the new container
            //For this reason we fake the container select box for design reasons
            $primaryContainerPath = $ContainersTable->getPathByIdAsString($host['Host']['container_id']);
            $primaryContainerPathSelect = Api::makeItJavaScriptAble([
                $host['Host']['container_id'] => $primaryContainerPath
            ]);

            $sharingContainers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
            if (isset($sharingContainers[$host['Host']['container_id']])) {
                //Remove primary container from result
                unset($sharingContainers[$host['Host']['container_id']]);
            }
            $sharingContainers = Api::makeItJavaScriptAble($sharingContainers);

            $this->set('host', $host);
            $this->set('primaryContainerPathSelect', $primaryContainerPathSelect);
            $this->set('sharingContainers', $sharingContainers);
            $this->viewBuilder()->setOption('serialize', [
                'host',
                'primaryContainerPathSelect',
                'sharingContainers'
            ]);
            return;
        }

        if ($this->request->is('post')) {
            $hostEntity = $HostsTable->get($id);

            //Only use this one field to avoid data manipulation!
            $newSharingContainers = [
                'hosts_to_containers_sharing' => $this->request->getData('Host.hosts_to_containers_sharing')
            ];

            //Always add primary container
            $newSharingContainers['hosts_to_containers_sharing']['_ids'][] = $hostEntity->get('container_id');

            $hostEntity = $HostsTable->patchEntity($hostEntity, $newSharingContainers);
            $HostsTable->save($hostEntity);
            if ($hostEntity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $hostEntity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors

                // @todo fix changelog
                /** @var  ChangelogsTable $ChangelogsTable */
                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');
                $requestData = $this->request->getData();

                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                    'edit',
                    'hosts',
                    $hostEntity->get('id'),
                    OBJECT_HOST,
                    $hostEntity->get('container_id'),
                    $User->getId(),
                    $hostEntity->get('name'),
                    array_merge($HostsTable->resolveDataForChangelog($requestData), $requestData),
                    array_merge($HostsTable->resolveDataForChangelog($hostForChangelog), $hostForChangelog)
                );
                if ($changelog_data) {
                    /** @var Changelog $changelogEntry */
                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                    $ChangelogsTable->save($changelogEntry);
                }

                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($hostEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('host', $hostEntity);
            $this->viewBuilder()->setOption('serialize', ['host']);
            return;
        }
    }

    /**
     * @deprecated
     */
    public function edit_details($host_id = null) {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);
        $this->set('back_url', $this->referer());

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        $containerIds = $this->MY_RIGHTS;
        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id');

        //get sharing containers
        $sharingContainers = $this->getSharingContainers(null, false);

        if ($this->request->is('post') || $this->request->is('put')) {
            foreach (func_get_args() as $host_id) {
                $this->Host->unbindModel([
                        'hasMany'             => ['Hostcommandargumentvalue', 'HostescalationHostMembership', 'HostdependencyHostMembership', 'Service', 'Customvariable'],
                        'hasAndBelongsToMany' => ['Parenthost', 'Hostgroup'],
                        'belongsTo'           => ['CheckPeriod', 'NotifyPeriod', 'CheckCommand'],
                    ]
                );
                $data = ['Host' => []];
                $host = $this->Host->findById($host_id);
                if (!empty($host)) {
                    //Fill up required fields
                    $data['Host']['id'] = $host['Host']['id'];
                    $data['Host']['container_id'] = $host['Host']['container_id'];
                    $data['Host']['name'] = $host['Host']['name'];
                    $data['Host']['hosttemplate_id'] = $host['Host']['hosttemplate_id'];
                    $data['Host']['address'] = $host['Host']['address'];

                    $hostSharingPermissions = new HostSharingPermissions(
                        $host['Host']['container_id'],
                        $this->hasRootPrivileges,
                        Hash::extract($host['Container'], '{n}.id'),
                        $this->MY_RIGHTS
                    );
                    $allowSharing = $hostSharingPermissions->allowSharing();

                    if ($allowSharing) {
                        if ($this->request->getData('Host.edit_sharing') == 1) {
                            if (!empty($this->request->getData('Host.shared_container'))) {
                                if ($this->request->getData('Host.keep_sharing') == 1) {
                                    $sharedContainer = Hash::extract($host, 'Container.{n}.id');
                                    $containers = array_merge($sharedContainer, $this->request->getData('Host.shared_container'));
                                    $data['Container']['Container'] = $containers;
                                } else {
                                    $containers = array_merge([$host['Host']['container_id']], $this->request->getData('Host.shared_container'));
                                    $data['Container']['Container'] = $containers;
                                }

                            }
                        }

                    }

                    if ($this->request->getData('Host.edit_description') == 1) {
                        $data['Host']['description'] = $this->request->getData('Host.description');
                    }

                    if ($this->request->getData('Host.edit_contacts') == 1) {
                        $_contacts = [];
                        if ($this->request->getData('Host.keep_contacts') == 1) {
                            if (!empty($host['Contact'])) {
                                //Merge exsting contacts with new contacts
                                $_contacts = Hash::extract($host['Contact'], '{n}.id');
                                $_contacts = Hash::merge($_contacts, $this->request->getData('Host.Contact'));
                                $_contacts = array_unique($_contacts);
                            } else {
                                // There are no old contacts to overwirte, wo we take the current request data
                                $_contacts = $this->request->getData('Host.Contact');
                            }
                        } else {
                            ////Overwrite all old contacts
                            $_contacts = $this->request->getData('Host.Contact');
                        }
                        $data['Host']['Contact'] = $_contacts;
                        $data['Contact'] = [
                            'Contact' => $_contacts,
                        ];
                    }

                    if ($this->request->getData('Host.edit_contactgroups') == 1) {
                        $_contactgroups = [];
                        if ($this->request->getData('Host.keep_contactgroups') == 1) {
                            if (!empty($host['Contactgroup'])) {
                                //Merge existing contactgroups to new contact groups
                                $_contactgroups = Hash::extract($host['Contactgroup'], '{n}.id');
                                $_contactgroups = Hash::merge($_contactgroups, $this->request->getData('Host.Contactgroup'));
                                $_contactgroups = array_unique($_contactgroups);
                            } else {
                                // There are no old contact groups to overwirte, wo we take the current request data
                                $_contactgroups = $this->request->getData('Host.Contactgroup');
                            }
                        } else {
                            //Overwrite all old contact groups
                            $_contactgroups = $this->request->getData('Host.Contactgroup');
                        }
                        $data['Host']['Contactgroup'] = $_contactgroups;
                        $data['Contactgroup'] = [
                            'Contactgroup' => $_contactgroups,
                        ];
                    }

                    if (!empty($data['Host']['Contact']) || !empty($data['Host']['Contactgroup'])) {
                        //Welcome to nagios 4 -.-
                        $data['Host']['own_contacts'] = 1;
                        $data['Host']['own_contactgroups'] = 1;
                    } else {
                        if (isset($_contacts) || isset($_contactgroups)) {
                            // Only if the user has submit a contact or a contact group, may be he want to delet all contacts.
                            $data['Host']['own_contacts'] = 0;
                            $data['Host']['own_contactgroups'] = 0;
                            $data['Host']['Contact'] = [];
                            $data['Host']['Contactgroup'] = [];
                        }
                    }

                    if ($this->request->getData('Host.edit_url') == 1) {
                        $data['Host']['host_url'] = $this->request->getData('Host.host_url');
                    }

                    if ($this->request->getData('Host.edit_tags') == 1) {
                        $data['Host']['tags'] = $this->request->getData('Host.tags');
                        if ($this->request->getData('Host.keep_tags') == 1) {
                            if (!empty($host['Host']['tags'])) {
                                //Host has tags, lets merge this
                                $data['Host']['tags'] = implode(',', array_unique(Hash::merge(explode(',', $host['Host']['tags']), explode(',', $data['Host']['tags']))));
                            } else {
                                if (!empty($host['Hosttemplate']['tags'])) {
                                    //The host has no own tags, lets merge from hosttemplate
                                    $data['Host']['tags'] = implode(',', array_unique(Hash::merge(explode(',', $host['Hosttemplate']['tags']), explode(',', $data['Host']['tags']))));
                                }
                            }
                        }
                    }

                    if ($this->request->getData('Host.edit_priority') == 1) {
                        $data['Host']['priority'] = $this->request->getData('Host.priority');
                    }
                    $this->Host->save($data);
                    unset($data);
                }
            }
            $this->setFlash(__('Host modified successfully'));
            $redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
            $this->redirect($redirect);

            return;
        }

        $this->set(compact(['contacts', 'contactgroups', 'sharingContainers']));
    }

    /**
     * @deprecated
     */
    public function getSharingContainers($containerId = null, $jsonOutput = true) {
        if ($jsonOutput) {
            $this->autoRender = false;
        }
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        $sharingContainers = array_diff_key($containers, [$containerId => $containerId]);

        if ($jsonOutput) {
            echo json_encode($sharingContainers);
        } else {
            return $sharingContainers;
        }
    }


    public function disabled() {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $Systemsettings->getMasterInstanceName();
        $SatelliteNames = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            $MY_RIGHTS = [];
            if ($this->hasRootPrivileges === false) {
                $MY_RIGHTS = $this->MY_RIGHTS;
            }
            /** @var $SatellitesTable \DistributeModule\Model\Table\SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');
            $SatelliteNames = $SatellitesTable->getSatellitesAsList($MY_RIGHTS);
            $SatelliteNames[0] = $masterInstanceName;
        }

        if (!$this->isApiRequest()) {
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }


        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostFilter = new HostFilter($this->request);
        $HostCondition = new HostConditions();
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $HostFilter->getPage());

        $HostCondition->setIncludeDisabled(true);
        if ($this->hasRootPrivileges === false) {
            $HostCondition->setContainerIds($this->MY_RIGHTS);
        }

        $hosts = $HostsTable->getHostsDisabled($HostFilter, $HostCondition, $PaginateOMat);


        $all_hosts = [];
        foreach ($hosts as $host) {
            $Host = new Host($host);
            $Hosttemplate = new Hosttemplate($host);

            $hostSharingPermissions = new HostSharingPermissions(
                $Host->getContainerId(), $this->hasRootPrivileges, $Host->getContainerIds(), $this->MY_RIGHTS
            );
            $allowSharing = $hostSharingPermissions->allowSharing();

            if ($this->hasRootPrivileges) {
                $allowEdit = true;
            } else {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $Host->getContainerIds());
                $allowEdit = $ContainerPermissions->hasPermission();
            }

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $SatelliteNames[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'         => $Host->toArray(),
                'Hosttemplate' => $Hosttemplate->toArray(),
                'Hoststatus'   => [
                    'isInMonitoring' => false,
                    'currentState'   => -1
                ]
            ];
            $tmpRecord['Host']['allow_sharing'] = $allowSharing;
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;
            $tmpRecord['Host']['allow_edit'] = $allowEdit;
            $all_hosts[] = $tmpRecord;
        }

        $this->set('all_hosts', $all_hosts);
        $toJson = ['all_hosts', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hosts', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * @deprecated
     */
    public function deactivate($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Host not found'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $host = $HostsTable->getHostById($id);
        $host->disabled = 1;

        if ($HostsTable->save($host)) {
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');
            $ServicesTable->updateAll([
                'disabled' => 1
            ], [
                'host_id' => $id
            ]);

            $this->set('success', true);
            $this->set('message', __('Host successfully disabled'));
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while disabling host'));
        $this->viewBuilder()->setOption('serialize', ['success', 'id', 'message']);
    }

    /**
     * @deprecated
     */
    public function enable($id = null) {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Host->id = $id;
        if ($this->Host->saveField('disabled', 0)) {
            $this->Service->updateAll(['Service.disabled' => 0], ['Service.host_id' => $id]);
            $this->set('success', true);
            $this->set('message', __('Host successfully enabled'));
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while enabling host'));
        $this->viewBuilder()->setOption('serialize', ['success', 'id', 'message']);
    }

    /**
     * @deprecated
     */
    public function delete($id = null) {
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $host = $this->Host->findById($id);
        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck)) {
            $this->render403();
            return;
        }

        $modules = $this->Constants->defines['modules'];

        $usedBy = $this->Host->isUsedByModules($host, $modules);
        if (empty($usedBy['host']) && empty($usedBy['service'])) {
            //Not used by any module
            if ($this->Host->__delete($host, $this->Auth->user('id'))) {

                /** @var $DocumentationsTable DocumentationsTable */
                $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');

                $DocumentationsTable->deleteDocumentationByUuid($host['Host']['uuid']);

                $this->set('success', true);
                $this->set('message', __('Host successfully deleted'));
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }
        }

        //both types must be host, otherwise the serviceUsedBy site with the host id will be displayed wich results in an error
        $usedBy = Hash::merge(
            $this->getUsedByForFrontend($usedBy['host'], 'host'),
            $this->getUsedByForFrontend($usedBy['service'], 'host')
        );

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->set('id', $id);
        $this->set('message', __('Issue while deleting host'));
        $this->set('usedBy', $usedBy);
        $this->viewBuilder()->setOption('serialize', ['success', 'id', 'message', 'usedBy']);
    }

    /**
     * @deprecated
     */
    public function copy($id = null) {
        $userId = $this->Auth->user('id');
        $validationErrors = [];
        if ($this->request->is('post') || $this->request->is('put')) {
            $validationError = false;
            $dataToSaveArray = [];
            $this->loadModel('Hosttemplate');
            //We want to save/validate the data and save it
            foreach ($this->request->data['Host'] as $key => $host2copy) {
                if (!$this->Host->exists($host2copy)) {
                    continue;
                }
                $sourceHost = $this->Host->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Host.name',
                        'Host.hosttemplate_id',
                        'Host.container_id',
                        'Host.check_period_id',
                        'Host.notify_period_id',
                        'Host.description',
                        'Host.command_id',
                        'Host.check_interval',
                        'Host.retry_interval',
                        'Host.max_check_attempts',
                        'Host.notification_interval',
                        'Host.notifications_enabled',
                        'Host.notify_on_down',
                        'Host.notify_on_unreachable',
                        'Host.notify_on_recovery',
                        'Host.notify_on_flapping',
                        'Host.notify_on_downtime',
                        'Host.flap_detection_enabled',
                        'Host.flap_detection_on_up',
                        'Host.flap_detection_on_down',
                        'Host.flap_detection_on_unreachable',
                        'Host.process_performance_data',
                        'Host.freshness_checks_enabled',
                        'Host.freshness_threshold',
                        'Host.notes',
                        'Host.priority',
                        'Host.tags',
                        'Host.host_url',
                        'Host.host_type',
                        'Host.own_contacts',
                        'Host.own_contactgroups',
                        'Host.own_customvariables',
                        'Host.satellite_id',
                        'Host.disabled'
                    ],
                    'contain'    => [
                        'Parenthost'               => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                        'Container'                => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                        'CheckPeriod'              => [
                            'fields' => [
                                'CheckPeriod.id',
                                'CheckPeriod.name'
                            ]
                        ],
                        'NotifyPeriod'             => [
                            'fields' => [
                                'NotifyPeriod.id',
                                'NotifyPeriod.name'
                            ]
                        ],
                        'CheckCommand'             => [
                            'fields' => [
                                'CheckCommand.id',
                                'CheckCommand.name',
                            ]
                        ],
                        'Contact'                  => [
                            'fields' => [
                                'Contact.id',
                                'Contact.name'
                            ],
                        ],
                        'Contactgroup'             => [
                            'fields'    => [
                                'Contactgroup.id',
                            ],
                            'Container' => [
                                'fields' => [
                                    'Container.name'
                                ]
                            ]
                        ],
                        'Hostcommandargumentvalue' => [
                            'fields' => [
                                'commandargument_id',
                                'value',
                            ],
                        ],
                        'Customvariable'           => [
                            'fields' => [
                                'name',
                                'value',
                                'objecttype_id'
                            ],
                        ],
                        'Hostgroup'                => [
                            'fields'    => [
                                'Hostgroup.id',
                            ],
                            'Container' => [
                                'fields' => [
                                    'Container.name'
                                ]
                            ]
                        ],
                    ],
                    'conditions' => [
                        'Host.id' => $host2copy['source']
                    ],
                ]);

                $hosttemplate = $this->Hosttemplate->find('first', [
                    'recursive'  => -1,
                    'contain'    => [
                        'Customvariable'                   => [
                            'fields' => [
                                'name',
                                'value',
                            ],
                        ],
                        'CheckPeriod'                      => [
                            'fields' => [
                                'CheckPeriod.id',
                                'CheckPeriod.name'
                            ]
                        ],
                        'NotifyPeriod'                     => [
                            'fields' => [
                                'NotifyPeriod.id',
                                'NotifyPeriod.name'
                            ]
                        ],
                        'CheckCommand'                     => [
                            'fields' => [
                                'CheckCommand.id',
                                'CheckCommand.name',
                            ]
                        ],
                        'Contact'                          => [
                            'fields' => [
                                'id',
                                'name',
                            ],
                        ],
                        'Contactgroup'                     => [
                            'fields'    => ['id'],
                            'Container' => [
                                'fields' => [
                                    'name',
                                ],
                            ],
                        ],
                        'Hostgroup'                        => [
                            'fields'    => ['id'],
                            'Container' => [
                                'fields' => [
                                    'name',
                                ],
                            ],
                        ],
                        'Hosttemplatecommandargumentvalue' => [
                            'fields' => [
                                'commandargument_id',
                                'value',
                            ],
                        ],
                    ],
                    'conditions' => [
                        'Hosttemplate.id' => $sourceHost['Host']['hosttemplate_id']
                    ]
                ]);

                $sourceHost = Hash::remove($sourceHost, 'Host.id');
                $sourceHost = Hash::remove($sourceHost, '{s}.{n}.{s}.host_id');


                $contactIds = (!empty($sourceHost['Contact'])) ? Hash::extract($sourceHost['Contact'], '{n}.id') : [];
                $contactgroupIds = (!empty($sourceHost['Contactgroup'])) ? Hash::extract($sourceHost['Contactgroup'], '{n}.id') : [];
                $hostgroupIds = (!empty($sourceHost['Hostgroup'])) ? Hash::extract($sourceHost['Hostgroup'], '{n}.id') : [];
                $customVariables = (!empty($sourceHost['Customvariable']) && !is_null($sourceHost['Customvariable'])) ? Hash::remove($sourceHost['Customvariable'], '{n}.object_id') : [];
                $parentHostIds = (!empty($sourceHost['Parenthost'])) ? Hash::extract($sourceHost['Parenthost'], '{n}.id') : [];
                $containerIds = (!empty($sourceHost['Container'])) ? Hash::extract($sourceHost['Container'], '{n}.id') : [];
                $newHostData = [
                    'Host'                     => Hash::merge(
                        $sourceHost['Host'], [
                        'uuid'         => UUID::v4(),
                        'name'         => $host2copy['name'],
                        'description'  => $host2copy['description'],
                        'host_url'     => $host2copy['host_url'],
                        'address'      => $host2copy['address'],
                        'Contact'      => $contactIds,
                        'Contactgroup' => $contactgroupIds,
                        'Hostgroup'    => $hostgroupIds,
                    ]),
                    'Contact'                  => ['Contact' => $contactIds],
                    'Contactgroup'             => ['Contactgroup' => $contactgroupIds],
                    'Hostgroup'                => ['Hostgroup' => $hostgroupIds],
                    'Container'                => ['Container' => $containerIds],
                    'Customvariable'           => $customVariables,
                    'Hostcommandargumentvalue' => (!empty($sourceHost['Hostcommandargumentvalue'])) ? Hash::remove($sourceHost['Hostcommandargumentvalue'], '{n}.host_id') : [],
                    'Parenthost'               => ['Parenthost' => $parentHostIds]
                ];
                /* Data for Changelog Start*/
                $sourceHost['Customvariable'] = $customVariables;
                $hosttemplate['Customvariable'] = (!empty($sourceHost['Customvariable']) && !is_null($sourceHost['Customvariable'])) ? Hash::remove($sourceHost['Customvariable'], '{n}.object_id') : [];


                if (!empty($sourceHost['Parenthost'])) {
                    $parenthosts = [];
                    foreach ($sourceHost['Parenthost'] as $parenthost) {
                        $parenthosts[] = [
                            'id'   => $parenthost['id'],
                            'name' => $parenthost['name']
                        ];
                    }
                    $sourceHost['Parenthost'] = $parenthosts;
                }
                if (!empty($sourceHost['Contactgroup'])) {
                    $contactgroups = [];
                    foreach ($sourceHost['Contactgroup'] as $contactgroup) {
                        $contactgroups[] = [
                            'id'   => $contactgroup['id'],
                            'name' => $contactgroup['Container']['name']
                        ];
                    }
                    $sourceHost['Contactgroup'] = $contactgroups;
                } else if (empty($sourceHost['Contactgroup']) && !empty($hosttemplate['Contactgroup'])) {
                    $contactgroups = [];
                    foreach ($hosttemplate['Contactgroup'] as $contactgroup) {
                        $contactgroups[] = [
                            'id'   => $contactgroup['id'],
                            'name' => $contactgroup['Container']['name']
                        ];
                    }
                    $hosttemplate['Contactgroup'] = $contactgroups;
                }

                if (!empty($sourceHost['Hostgroup'])) {
                    $hostgroups = [];
                    foreach ($sourceHost['Hostgroup'] as $hostgroup) {
                        $hostgroups[] = [
                            'id'   => $hostgroup['id'],
                            'name' => $hostgroup['Container']['name']
                        ];
                    }
                    $sourceHost['Hostgroup'] = $hostgroups;
                } else if (empty($sourceHost['Hostgroup']) && !empty($hosttemplate['Hostgroup'])) {
                    $hostgroups = [];
                    foreach ($hosttemplate['Hostgroup'] as $hostgroup) {
                        $hostgroups[] = [
                            'id'   => $hostgroup['id'],
                            'name' => $hostgroup['Container']['name']
                        ];
                    }
                    $hosttemplate['Hostgroup'] = $hostgroups;
                }
                /* Data for Changelog End*/
                $this->Host->set($newHostData);

                if ($this->Host->validates()) {
                    $dataToSaveArray[$host2copy['source']] = $newHostData;
                    $dataForChangeLog[$host2copy['source']] = [
                        'Host'         => $sourceHost,
                        'Hosttemplate' => $hosttemplate
                    ];
                } else {
                    $validationError = true;
                }
                if (!empty($this->Host->validationErrors)) {
                    $validationErrors['Host'][$key] = $this->Host->validationErrors;
                }
            }
            if ($validationError === false) {
                //All data is valid we can create the copy of the host
                $this->loadModel('Service');
                $this->loadModel('Servicetemplate');
                foreach ($dataToSaveArray as $sourceHostId => $data) {
                    $this->Host->create();
                    if ($this->Host->saveAll($data)) {
                        $hostDataAfterSave = $this->Host->dataForChangelogCopy($dataForChangeLog[$sourceHostId]['Host'], $dataForChangeLog[$sourceHostId]['Hosttemplate']);
                        /** @var  ChangelogsTable $ChangelogsTable */
                        $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                        $changelog_data = $ChangelogsTable->parseDataForChangelog(
                            $this->request->getParam('action'),
                            $this->request->getParam('controller'),
                            $this->Host->id,
                            OBJECT_HOST,
                            $data['Host']['container_id'],
                            $userId,
                            $data['Host']['name'],
                            $hostDataAfterSave
                        );
                        if ($changelog_data) {
                            /** @var Changelog $changelogEntry */
                            $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                            $ChangelogsTable->save($changelogEntry);
                        }
                        $hostId = $this->Host->id;
                        $services = $this->Service->find('all', [
                            'recursive'  => -1,
                            'fields'     => [
                                'Service.name',
                                'Service.servicetemplate_id',
                                'Service.check_period_id',
                                'Service.notify_period_id',
                                'Service.description',
                                'Service.command_id',
                                'Service.eventhandler_command_id',
                                'Service.check_interval',
                                'Service.retry_interval',
                                'Service.max_check_attempts',
                                'Service.notification_interval',
                                'Service.notifications_enabled',
                                'Service.notify_on_warning',
                                'Service.notify_on_unknown',
                                'Service.notify_on_critical',
                                'Service.notify_on_recovery',
                                'Service.notify_on_flapping',
                                'Service.notify_on_downtime',
                                'Service.flap_detection_enabled',
                                'Service.flap_detection_on_ok',
                                'Service.flap_detection_on_warning',
                                'Service.flap_detection_on_unknown',
                                'Service.flap_detection_on_critical',
                                'Service.process_performance_data',
                                'Service.freshness_checks_enabled',
                                'Service.freshness_threshold',
                                'Service.notes',
                                'Service.priority',
                                'Service.tags',
                                'Service.service_url',
                                'Service.is_volatile',
                                'Service.service_type',
                                'Service.own_contacts',
                                'Service.own_contactgroups',
                                'Service.own_customvariables',
                                'Service.disabled'
                            ],
                            'contain'    => [
                                'CheckPeriod'                      => [
                                    'fields' => [
                                        'CheckPeriod.id',
                                        'CheckPeriod.name'
                                    ]
                                ],
                                'NotifyPeriod'                     => [
                                    'fields' => [
                                        'NotifyPeriod.id',
                                        'NotifyPeriod.name'
                                    ]
                                ],
                                'CheckCommand'                     => [
                                    'fields' => [
                                        'CheckCommand.id',
                                        'CheckCommand.name',
                                    ]
                                ],
                                'Contact'                          => [
                                    'fields' => [
                                        'Contact.id',
                                        'Contact.name'
                                    ],
                                ],
                                'Contactgroup'                     => [
                                    'fields'    => [
                                        'Contactgroup.id',
                                    ],
                                    'Container' => [
                                        'fields' => [
                                            'Container.name'
                                        ]
                                    ]
                                ],
                                'Servicecommandargumentvalue'      => [
                                    'fields' => [
                                        'commandargument_id', 'value',
                                    ],
                                ],
                                'Serviceeventcommandargumentvalue' => [
                                    'fields' => [
                                        'commandargument_id', 'value',
                                    ],
                                ],
                                'Customvariable'                   => [
                                    'fields' => [
                                        'name',
                                        'value',
                                        'objecttype_id'
                                    ],
                                ],
                                'Servicegroup'                     => [
                                    'fields'    => [
                                        'Servicegroup.id',
                                    ],
                                    'Container' => [
                                        'fields' => [
                                            'Container.name'
                                        ]
                                    ]
                                ],
                            ],
                            'conditions' => [
                                'Service.host_id'      => $sourceHostId,
                                'Service.service_type' => $this->Service->serviceTypes('copy'),
                            ],
                        ]);

                        //A Cache for servicetemplates to reduce the SQL querys
                        $servicetemplates = [];
                        foreach ($services as $service) {
                            if (isset($servicetemplates[$service['Service']['servicetemplate_id']])) {
                                $servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
                            } else {
                                $servicetemplates[$service['Service']['servicetemplate_id']] = $this->Servicetemplate->find('first', [
                                        'recursive'  => -1,
                                        'fields'     => [
                                            'Servicetemplate.template_name',
                                            'Servicetemplate.name',
                                            'Servicetemplate.check_period_id',
                                            'Servicetemplate.notify_period_id',
                                            'Servicetemplate.description',
                                            'Servicetemplate.command_id',
                                            'Servicetemplate.eventhandler_command_id',
                                            'Servicetemplate.check_interval',
                                            'Servicetemplate.retry_interval',
                                            'Servicetemplate.max_check_attempts',
                                            'Servicetemplate.notification_interval',
                                            'Servicetemplate.notifications_enabled',
                                            'Servicetemplate.notify_on_warning',
                                            'Servicetemplate.notify_on_unknown',
                                            'Servicetemplate.notify_on_critical',
                                            'Servicetemplate.notify_on_recovery',
                                            'Servicetemplate.notify_on_flapping',
                                            'Servicetemplate.notify_on_downtime',
                                            'Servicetemplate.flap_detection_enabled',
                                            'Servicetemplate.flap_detection_on_ok',
                                            'Servicetemplate.flap_detection_on_warning',
                                            'Servicetemplate.flap_detection_on_unknown',
                                            'Servicetemplate.flap_detection_on_critical',
                                            'Servicetemplate.process_performance_data',
                                            'Servicetemplate.freshness_checks_enabled',
                                            'Servicetemplate.freshness_threshold',
                                            'Servicetemplate.notes',
                                            'Servicetemplate.priority',
                                            'Servicetemplate.tags',
                                            'Servicetemplate.service_url',
                                            'Servicetemplate.is_volatile',
                                            'Servicetemplate.check_freshness',
                                        ],
                                        'contain'    => [
                                            'CheckPeriod'                              => [
                                                'fields' => [
                                                    'CheckPeriod.id',
                                                    'CheckPeriod.name'
                                                ]
                                            ],
                                            'NotifyPeriod'                             => [
                                                'fields' => [
                                                    'NotifyPeriod.id',
                                                    'NotifyPeriod.name'
                                                ]
                                            ],
                                            'CheckCommand'                             => [
                                                'fields' => [
                                                    'CheckCommand.id',
                                                    'CheckCommand.name',
                                                ]
                                            ],
                                            'Contact'                                  => [
                                                'fields' => [
                                                    'Contact.id',
                                                    'Contact.name'
                                                ],
                                            ],
                                            'Contactgroup'                             => [
                                                'fields'    => [
                                                    'Contactgroup.id',
                                                ],
                                                'Container' => [
                                                    'fields' => [
                                                        'Container.name'
                                                    ]
                                                ]
                                            ],
                                            'Servicegroup'                             => [
                                                'fields'    => [
                                                    'Servicegroup.id',
                                                ],
                                                'Container' => [
                                                    'fields' => [
                                                        'Container.name'
                                                    ]
                                                ]
                                            ],
                                            'Servicetemplatecommandargumentvalue'      => [
                                                'fields' => [
                                                    'id',
                                                    'commandargument_id',
                                                    'value',
                                                ],
                                            ],
                                            'Servicetemplateeventcommandargumentvalue' => [
                                                'fields' => [
                                                    'id',
                                                    'commandargument_id',
                                                    'value',
                                                ],
                                            ],
                                            'Customvariable'                           => [
                                                'fields' => [
                                                    'name', 'value',
                                                ],
                                            ],
                                        ],
                                        'conditions' => [
                                            'Servicetemplate.id' => $service['Service']['servicetemplate_id']
                                        ]
                                    ]
                                );
                                $servicetemplate = $servicetemplates[$service['Service']['servicetemplate_id']];
                            }
                            $service = Hash::remove($service, 'Service.id');
                            $service = Hash::remove($service, '{s}.{n}.{s}.service_id');
                            $contactIds = (!empty($service['Contact'])) ? Hash::extract($service['Contact'], '{n}.id') : [];
                            $contactgroupIds = (!empty($service['Contactgroup'])) ? Hash::extract($service['Contactgroup'], '{n}.id') : [];
                            $servicegroupIds = (!empty($service['Servicegroup'])) ? Hash::extract($service['Servicegroup'], '{n}.id') : [];
                            $customVariables = (!empty($service['Customvariable'])) ? Hash::remove($service['Customvariable'], '{n}.object_id') : [];
                            $newServiceData = [
                                'Service'                          => Hash::merge(
                                    $service['Service'], [
                                    'uuid'         => UUID::v4(),
                                    'host_id'      => $hostId,
                                    'Contact'      => $contactIds,
                                    'Contactgroup' => $contactgroupIds,
                                    'Servicegroup' => $servicegroupIds
                                ]),
                                'Contact'                          => ['Contact' => $contactIds],
                                'Contactgroup'                     => ['Contactgroup' => $contactgroupIds],
                                'Servicegroup'                     => ['Servicegroup' => $servicegroupIds],
                                'Customvariable'                   => $customVariables,
                                'Servicecommandargumentvalue'      => (!empty($service['Servicecommandargumentvalue'])) ? Hash::remove($service['Servicecommandargumentvalue'], '{n}.service_id') : [],
                                'Serviceeventcommandargumentvalue' => (!empty($service['Serviceeventcommandargumentvalue'])) ? Hash::remove($service['Serviceeventcommandargumentvalue'], '{n}.service_id') : [],
                            ];

                            /* Data for Changelog Start*/
                            $service['Host'] = ['id' => $hostId, 'name' => $data['Host']['name']];
                            $service['Customvariable'] = $customVariables;
                            $servicetemplate['Customvariable'] = (!empty($service['Customvariable']) && !is_null($service['Customvariable'])) ? Hash::remove($service['Customvariable'], '{n}.object_id') : [];

                            if (!empty($service['Contactgroup'])) {
                                $contactgroups = [];
                                foreach ($service['Contactgroup'] as $contactgroup) {
                                    $contactgroups[] = [
                                        'id'   => $contactgroup['id'],
                                        'name' => $contactgroup['Container']['name']
                                    ];
                                }
                                $service['Contactgroup'] = $contactgroups;
                            } else if (empty($service['Contactgroup']) && !empty($servicetemplate['Contactgroup'])) {
                                $contactgroups = [];
                                foreach ($servicetemplate['Contactgroup'] as $contactgroup) {
                                    $contactgroups[] = [
                                        'id'   => $contactgroup['id'],
                                        'name' => $contactgroup['Container']['name']
                                    ];
                                }
                                $servicetemplate['Contactgroup'] = $contactgroups;
                            }

                            if (!empty($service['Servicegroup'])) {
                                $servicegroups = [];
                                foreach ($service['Servicegroup'] as $servicegroup) {
                                    $servicegroups[] = [
                                        'id'   => $servicegroup['id'],
                                        'name' => $servicegroup['Container']['name']
                                    ];
                                }
                                $service['Servicegroup'] = $servicegroups;
                            } else if (empty($service['Servicegroup']) && !empty($servicetemplate['Servicegroup'])) {
                                $servicegroups = [];
                                foreach ($servicetemplate['Servicegroup'] as $servicegroup) {
                                    $servicegroups[] = [
                                        'id'   => $servicegroup['id'],
                                        'name' => $servicegroup['Container']['name']
                                    ];
                                }
                                $servicetemplate['Servicegroup'] = $servicegroups;
                            }
                            /* Data for Changelog End*/
                            $this->Service->create();
                            if ($this->Service->saveAll($newServiceData)) {
                                $serviceDataAfterSave = $this->Service->dataForChangelogCopy($service, $servicetemplate);
                                /** @var  ChangelogsTable $ChangelogsTable */
                                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                                    $this->request->getParam('action'),
                                    'services',
                                    $this->Service->id,
                                    OBJECT_SERVICE,
                                    $data['Host']['container_id'],
                                    $userId,
                                    $data['Host']['name'] . '/' . $serviceDataAfterSave['Service']['name'],
                                    $serviceDataAfterSave
                                );
                                if ($changelog_data) {
                                    /** @var Changelog $changelogEntry */
                                    $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                                    $ChangelogsTable->save($changelogEntry);
                                }
                            }
                        }
                    }
                }
                $this->setFlash(__('Host copied successfully'));
                $redirect = $this->Host->redirect($this->request->params, ['action' => 'index']);
                $this->redirect($redirect);
            } else {
                if (isset($validationErrors['Host'])) {
                    $this->Host->validationErrors = $validationErrors['Host'];
                    $this->setFlash(__('Could not copy host/s'), false);
                    /*
                    For multiple "line" validation errors the array we gibe the view needs to look like this:
                    array(
                        (int) 0 => array(
                            'name' => array(
                                (int) 0 => 'This field cannot be left blank.'
                            ),
                            'address' => array(
                                (int) 0 => 'This field cannot be left blank.'
                            )
                        ),
                        (int) 1 => array(
                            'address' => array(
                                (int) 0 => 'This field cannot be left blank.'
                            )
                        )
                    )
                    */
                }
            }
        }

        //We want to copy a host and display the view
        $hosts = [];
        foreach (func_get_args() as $host_id) {
            if ($this->Host->exists($host_id)) {
                $hosts[] = $this->Host->findById($host_id);
                //debug($host);
            }
        }
        $this->set(compact(['hosts']));
        $this->set('back_url', $this->referer());
    }

    /**
     * @deprecated
     */
    public function browser($idOrUuid = null) {
        if (!$this->isAngularJsRequest() && $idOrUuid === null) {
            //AngularJS loads the HTML template via https://xxx/hosts/browser.html
            $User = new User($this->getUser());
            $ModuleManager = new ModuleManager('GrafanaModule');
            if ($ModuleManager->moduleExists()) {
                $this->loadModel('GrafanaModule.GrafanaDashboard');
                $this->loadModel('GrafanaModule.GrafanaConfiguration');
                $grafanaConfiguration = $this->GrafanaConfiguration->find('first');
                if (!empty($grafanaConfiguration)) {
                    $GrafanaConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                    $this->set('GrafanaConfiguration', $GrafanaConfiguration);
                }
            }
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            $this->set('masterInstanceName', $Systemsettings->getMasterInstanceName());
            //Only ship template
            return;
        }

        $id = $idOrUuid;
        if (!is_numeric($idOrUuid)) {
            if (preg_match(UUID::regex(), $idOrUuid)) {
                $lookupHost = $this->Host->find('first', [
                    'recursive'  => -1,
                    'fields'     => [
                        'Host.id'
                    ],
                    'conditions' => [
                        'Host.uuid' => $idOrUuid
                    ]
                ]);
                if (empty($lookupHost)) {
                    throw new NotFoundException(__('Host not found'));
                }
                $this->redirect([
                    'controller' => 'hosts',
                    'action'     => 'browser',
                    $lookupHost['Host']['id']
                ]);
                return;
            }
        }

        /** @var $DocumentationsTable DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');

        unset($idOrUuid);
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }
        $rawHost = $this->Host->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.name',
                'Host.address',
                'Host.container_id',
                'Host.host_type',
                'Host.host_url',
            ],
            'contain'    => [
                'Container',
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.host_url'
                    ]
                ]
            ],
            'conditions' => [
                'Host.id' => $id
            ]
        ]);
        if ($rawHost['Host']['host_url'] === '' || $rawHost['Host']['host_url'] === null) {
            $rawHost['Host']['host_url'] = $rawHost['Hosttemplate']['host_url'];
        }
        $containerIdsToCheck = Hash::extract($rawHost, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $rawHost['Host']['container_id'];
        //Check if user is permitted to see this object
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }
        if ($this->hasRootPrivileges) {
            $allowEdit = true;
        } else {
            $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
            $allowEdit = $ContainerPermissions->hasPermission();
        }

        $hostQuery = $this->Host->getQueryForBrowser($id);
        $host = $this->Host->find('first', $hostQuery);
        $hosttemplateQuery = $this->Hosttemplate->getQueryForBrowser($host['Host']['hosttemplate_id']);
        $hosttemplate = $this->Hosttemplate->find('first', $hosttemplateQuery);
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $HosttemplateMerger = new HosttemplateMerger($host, $hosttemplate);
        $mergedHost = [
            'Host'                        => $HosttemplateMerger->mergeHostWithTemplate(),
            'CheckPeriod'                 => $HosttemplateMerger->mergeCheckPeriod(),
            'NotifyPeriod'                => $HosttemplateMerger->mergeNotifyPeriod(),
            'CheckCommand'                => $HosttemplateMerger->mergeCheckCommand(),
            'Customvariable'              => $HosttemplateMerger->mergeCustomvariables(),
            'Hostcommandargumentvalue'    => $HosttemplateMerger->mergeCommandargumentsForReplace(),
            'Contactgroup'                => $HosttemplateMerger->mergeContactgroups(),
            'Contact'                     => $HosttemplateMerger->mergeContacts(),
            'areContactsFromHost'         => $HosttemplateMerger->areContactsFromHost(),
            'areContactsFromHosttemplate' => $HosttemplateMerger->areContactsFromHosttemplate(),
        ];
        $mergedHost['Host']['allowEdit'] = $allowEdit;
        $mergedHost['Host']['satelliteId'] = (int)$mergedHost['Host']['satellite_id'];
        $mergedHost['Host']['is_satellite_host'] = $mergedHost['Host']['satelliteId'] !== 0;
        $mergedHost['checkIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['Host']['check_interval']);
        $mergedHost['retryIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['Host']['retry_interval']);
        $mergedHost['notificationIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['Host']['notification_interval']);
        // Replace $HOSTNAME$
        $HostMacroReplacerCommandLine = new HostMacroReplacer($host);
        $hostCommandLine = $HostMacroReplacerCommandLine->replaceBasicMacros($mergedHost['CheckCommand']['command_line']);

        $mergedHost['Host']['host_url_replaced'] = $mergedHost['Host']['host_url'];
        if ($mergedHost['Host']['host_url'] !== '' && $mergedHost['Host']['host_url'] !== null) {
            $mergedHost['Host']['host_url_replaced'] = $HostMacroReplacerCommandLine->replaceBasicMacros($mergedHost['Host']['host_url']);
        }

        // Replace $_HOSTFOOBAR$
        $HostCustomMacroReplacerCommandLine = new CustomMacroReplacer($mergedHost['Customvariable'], OBJECT_HOST);
        $hostCommandLine = $HostCustomMacroReplacerCommandLine->replaceAllMacros($hostCommandLine);
        // Replace Command args $ARGx$
        $hostCommandLine = str_replace(
            array_keys($mergedHost['Hostcommandargumentvalue']),
            array_values($mergedHost['Hostcommandargumentvalue']),
            $hostCommandLine
        );
        $mergedHost['hostCommandLine'] = $hostCommandLine;
        //Check permissions for Contacts
        $contactsWithContainers = [];
        $writeContainers = $this->getWriteContainers();
        foreach ($mergedHost['Contact'] as $key => $contact) {
            $contactsWithContainers[$contact['id']] = [];
            foreach ($contact['Container'] as $container) {
                $contactsWithContainers[$contact['id']][] = $container['id'];
            }
            $mergedHost['Contact'][$key]['allowEdit'] = true;
            if ($this->hasRootPrivileges === false) {
                $all_contacts[$key]['allowEdit'] = false;
                if (!empty(array_intersect($contactsWithContainers[$contact['id']], $writeContainers))) {
                    $all_contacts[$key]['allowEdit'] = true;
                }
            }
        }

        /** @var $HoststatusTable HoststatusTableInterface */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();

        //Check permissions for Contact groups
        foreach ($mergedHost['Contactgroup'] as $key => $contactgroup) {
            $mergedHost['Contactgroup'][$key]['allowEdit'] = $this->isWritableContainer($contactgroup['Container']['parent_id']);
        }
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->wildcard();
        $HoststatusConditions = new HoststatusConditions($this->DbBackend);
        //$HoststatusConditions->hostsDownAndUnreachable();
        $hoststatus = $HoststatusTable->byUuid($host['Host']['uuid'], $HoststatusFields);
        if (empty($hoststatus)) {
            //Empty host state for Hoststatus object
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $Hoststatus = new Hoststatus($hoststatus['Hoststatus'], $UserTime);
        $hoststatus = $Hoststatus->toArrayForBrowser();
        $hoststatus['longOutputHtml'] = $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($Hoststatus->getLongOutput(), true));
        $parenthosts = $host['Parenthost'];
        $ParentHoststatusFields = new HoststatusFields($this->DbBackend);
        $ParentHoststatusFields->currentState()->lastStateChange();
        $parentHostStatusRaw = $HoststatusTable->byUuid(
            Hash::extract($host['Parenthost'], '{n}.uuid'),
            $ParentHoststatusFields,
            $HoststatusConditions
        );
        $parentHostStatus = [];
        foreach ($parentHostStatusRaw as $uuid => $parentHoststatus) {
            $ParentHoststatus = new Hoststatus($parentHoststatus['Hoststatus'], $UserTime);
            $parentHostStatus[$uuid] = $ParentHoststatus->toArrayForBrowser();
        }
        //Get Containers
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $mainContainer = $ContainersTable->treePath($rawHost['Host']['container_id']);
        //Add shared containers
        $sharedContainers = [];
        foreach ($rawHost['Container'] as $container) {
            if (isset($container['id']) && $container['id'] != $rawHost['Host']['container_id']) {
                $sharedContainers[$container['id']] = $ContainersTable->treePath($container['id']);
            }
        }
        $acknowledgement = [];
        if ($Hoststatus->isAcknowledged()) {
            $acknowledgement = $this->AcknowledgedHost->byHostUuid($host['Host']['uuid']);
            if (!empty($acknowledgement)) {
                $Acknowledgement = new AcknowledgementHost($acknowledgement['AcknowledgedHost'], $UserTime);
                $acknowledgement = $Acknowledgement->toArray();
                $ticketSystem = $this->Systemsetting->find('first', [
                    'conditions' => ['key' => 'TICKET_SYSTEM.URL'],
                ]);
                $ticketDetails = [];
                if (!empty($ticketSystem['Systemsetting']['value']) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $Acknowledgement->getCommentData(), $ticketDetails)) {
                    $commentDataHtml = $Acknowledgement->getCommentData();
                    if (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) {
                        $commentDataHtml = sprintf(
                            '<a href="%s%s" target="_blank">%s %s</a>',
                            $ticketSystem['Systemsetting']['value'],
                            $ticketDetails[3],
                            $ticketDetails[1],
                            $ticketDetails[2]
                        );
                    }
                } else {
                    $commentDataHtml = $this->Bbcode->asHtml($Acknowledgement->getCommentData(), true);
                }
                $acknowledgement['commentDataHtml'] = $commentDataHtml;
            }
        }
        $downtime = [];
        if ($Hoststatus->isInDowntime()) {
            $downtime = $this->DowntimeHost->byHostUuid($host['Host']['uuid'], true);
            if (!empty($downtime)) {
                $Downtime = new Downtime($downtime['DowntimeHost'], $allowEdit, $UserTime);
                $downtime = $Downtime->toArray();
            }
        }
        $canSubmitExternalCommands = $this->hasPermission('externalcommands', 'hosts');
        $this->set('mergedHost', $mergedHost);
        $this->set('docuExists', $DocumentationsTable->existsByUuid($rawHost['Host']['uuid']));
        $this->set('hoststatus', $hoststatus);
        $this->set('mainContainer', $mainContainer);
        $this->set('sharedContainers', $sharedContainers);
        $this->set('parenthosts', $parenthosts);
        $this->set('parentHostStatus', $parentHostStatus);
        $this->set('acknowledgement', $acknowledgement);
        $this->set('downtime', $downtime);
        $this->set('canSubmitExternalCommands', $canSubmitExternalCommands);
        $this->viewBuilder()->setOption('serialize', [
            'mergedHost',
            'docuExists',
            'hoststatus',
            'mainContainer',
            'sharedContainers',
            'parenthosts',
            'parentHostStatus',
            'acknowledgement',
            'downtime',
            'canSubmitExternalCommands'
        ]);
    }

    /**
     * Converts BB code to HTML
     *
     * @param string $uuid The hosts UUID you want to get the long output
     * @param bool $parseBbcode If you want to convert BB Code to HTML
     * @param bool $nl2br If you want to replace \n with <br>
     *
     * @return string
     * @deprecated
     */
    public function longOutputByUuid($uuid = null, $parseBbcode = true, $nl2br = true) {
        $this->autoRender = false;
        $result = $this->Host->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Host.id',
                'Host.uuid'
            ],
            'conditions' => [
                'Host.uuid' => $uuid
            ]
        ]);
        if (!empty($result)) {
            $Hoststatusfields = new HoststatusFields($this->DbBackend);
            $Hoststatusfields->longOutput();
            $hoststatus = $this->Hoststatus->byUuid($result['Host']['uuid'], $Hoststatusfields);
            if (!empty($hoststatus)) {
                if ($parseBbcode === true) {
                    if ($nl2br === true) {
                        return $this->Bbcode->nagiosNl2br($this->Bbcode->asHtml($hoststatus['Hoststatus']['long_output'], $nl2br));
                    } else {
                        return $this->Bbcode->asHtml($hoststatus['Hoststatus']['long_output'], $nl2br);
                    }
                }

                return $hoststatus['Hoststatus']['long_output'];
            }
        }

        return '';
    }

    /**
     * @deprecated
     */
    public function listToPdf() {
        $this->layout = 'Admin.default';
        $HostFilter = new HostFilter($this->request);

        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        if ($HostControllerRequest->isRequestFromBrowser() === false) {
            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($this->MY_RIGHTS);
        }

        if ($HostControllerRequest->isRequestFromBrowser() === true) {
            $browserContainerIds = $HostControllerRequest->getBrowserContainerIdsByRequest();
            foreach ($browserContainerIds as $containerIdToCheck) {
                if (!in_array($containerIdToCheck, $this->MY_RIGHTS)) {
                    $this->render403();
                    return;
                }
            }

            $HostCondition->setIncludeDisabled(false);
            $HostCondition->setContainerIds($browserContainerIds);

            if ($User->isRecursiveBrowserEnabled()) {
                //get recursive container ids
                $containerIdToResolve = $browserContainerIds;
                $containerIds = Hash::extract($this->Container->children($containerIdToResolve[0], false, ['Container.id']), '{n}.Container.id');
                $recursiveContainerIds = [];
                foreach ($containerIds as $containerId) {
                    if (in_array($containerId, $this->MY_RIGHTS)) {
                        $recursiveContainerIds[] = $containerId;
                    }
                }
                $HostCondition->setContainerIds(array_merge($HostCondition->getContainerIds(), $recursiveContainerIds));
            }
        }

        $HostCondition->setOrder($HostControllerRequest->getOrder([
            'Host.name' => 'asc'
        ]));


        if ($this->DbBackend->isNdoUtils()) {
            $query = $this->Host->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            $this->Host->virtualFieldsForIndex();
            $modelName = 'Host';
        }

        if ($this->DbBackend->isCrateDb()) {
            $query = $this->Hoststatus->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            $modelName = 'Hoststatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $query = $this->Host->getHostIndexQueryStatusengine3($HostCondition, $HostFilter->indexFilter());
            $this->Host->virtualFieldsForIndex();
            $modelName = 'Host';
        }

        if (isset($query['limit'])) {
            unset($query['limit']);
        }
        $all_hosts = $this->{$modelName}->find('all', $query);

        $this->set('all_hosts', $all_hosts);
        $this->set('UserTime', $UserTime);

        $filename = 'Hosts_' . strtotime('now') . '.pdf';
        $binary_path = '/usr/bin/wkhtmltopdf';
        if (file_exists('/usr/local/bin/wkhtmltopdf')) {
            $binary_path = '/usr/local/bin/wkhtmltopdf';
        }
        $this->pdfConfig = [
            'engine'             => 'CakePdf.WkHtmlToPdf',
            'margin'             => [
                'bottom' => 15,
                'left'   => 0,
                'right'  => 0,
                'top'    => 15,
            ],
            'encoding'           => 'UTF-8',
            'download'           => true,
            'binary'             => $binary_path,
            'orientation'        => 'portrait',
            'filename'           => $filename,
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }

    //Only for ACLs
    public function checkcommand() {
        return null;
    }

    //Only for ACLs
    public function externalcommands() {
        return null;
    }

    /**
     * @deprecated
     */
    public function loadHostById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
                'Hosttemplate'
            ],
        ]);

        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        foreach ($host['Host'] as $key => $value) {
            if ($host['Host'][$key] === '' || $host['Host'][$key] === null) {
                if (isset($host['Hosttemplate'][$key])) {
                    $host['Host'][$key] = $host['Hosttemplate'][$key];
                }
            }
        }

        $host['Host']['is_satellite_host'] = (int)$host['Host']['satellite_id'] !== 0;
        $host['Host']['allow_edit'] = false;
        if ($this->hasRootPrivileges === true) {
            $host['Host']['allow_edit'] = true;
        } else {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
                $host['Host']['allow_edit'] = $ContainerPermissions->hasPermission();
            }
        }


        unset($host['Hosttemplate']);
        $this->set('host', $host);
        $this->viewBuilder()->setOption('serialize', ['host']);
    }

    /**
     * @param string | null $uuid
     * @deprecated
     */
    public function hoststatus($uuid = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$uuid) {
            throw new NotFoundException(__('Invalid request parameter'));
        }

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();
        $hoststatus = $this->Hoststatus->byUuid($uuid, $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $this->set('hoststatus', $hoststatus);
        $this->viewBuilder()->setOption('serialize', ['hoststatus']);
    }

    /**
     * @deprecated
     */
    public function timeline($id = null) {
        session_write_close();
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Host->exists($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $id,
            ],
            'contain'    => [
                'Container',
                'Hosttemplate' => [
                    'fields' => [
                        'Hosttemplate.check_period_id',
                        'Hosttemplate.notify_period_id'
                    ]
                ]
            ],
            'fields'     => [
                'Host.uuid',
                'Host.container_id',
                'Container.*',
                'Host.check_period_id',
                'Host.notify_period_id'
            ]
        ]);

        $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $host['Host']['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        $timeperiodId = ($host['Host']['check_period_id']) ? $host['Host']['check_period_id'] : $host['Hosttemplate']['check_period_id'];
        //$notifyPeriodId = ($host['Host']['notify_period_id']) ? $host['Host']['notify_period_id'] : $host['Hosttemplate']['notify_period_id'];

        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $checkTimePeriod = $TimeperiodsTable->getTimeperiodWithTimerangesById($timeperiodId);

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        $Groups = new Groups();
        $this->set('groups', $Groups->serialize(true));


        //Process conditions
        $Conditions = new StatehistoryHostConditions();
        $Conditions->setOrder(['StatehistoryHost.state_time' => 'asc']);

        $start = $this->request->getQuery('start');
        $end = $this->request->getQuery('end');


        if (!is_numeric($start) || $start < 0) {
            $start = time() - 2 * 24 * 3600;
        }


        if (!is_numeric($end) || $end < 0) {
            $end = time();
        }
        $timeRanges = $this->DateRange->createDateRanges(
            date('d-m-Y H:i:s', $start),
            date('d-m-Y H:i:s', $end),
            $checkTimePeriod['Timeperiod']['timeperiod_timeranges']
        );

        $TimeRangeSerializer = new TimeRangeSerializer($timeRanges, $UserTime);
        $this->set('timeranges', $TimeRangeSerializer->serialize());
        unset($TimeRangeSerializer, $timeRanges);

        $hostUuid = $host['Host']['uuid'];

        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);
        $Conditions->setUseLimit(false);

        //Query state history records for hosts
        $query = $this->StatehistoryHost->getQuery($Conditions);
        $statehistories = $this->StatehistoryHost->find('all', $query);
        $statehistoryRecords = [];

        //Host has no state history record for selected time range
        //Get last available state history record for this host
        $query = $this->StatehistoryHost->getLastRecord($Conditions);
        $record = $this->StatehistoryHost->find('first', $query);
        if (!empty($record)) {
            $record['StatehistoryHost']['state_time'] = $start;
            $StatehistoryHost = new StatehistoryHost($record['StatehistoryHost']);
            $statehistoryRecords[] = $StatehistoryHost;
        }
        if (empty($statehistories) && empty($record)) {
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->currentState()
                ->isHardstate()
                ->lastStateChange()
                ->lastHardStateChange();

            $hoststatus = $this->Hoststatus->byUuid($host['Host']['uuid'], $HoststatusFields);
            if (!empty($hoststatus)) {
                $record['StatehistoryHost']['state_time'] = $hoststatus['Hoststatus']['last_state_change'];
                $record['StatehistoryHost']['state'] = $hoststatus['Hoststatus']['current_state'];
                $record['StatehistoryHost']['state_type'] = ($hoststatus['Hoststatus']['state_type']) ? true : false;
                $StatehistoryHost = new StatehistoryHost($record['StatehistoryHost']);
                $statehistoryRecords[] = $StatehistoryHost;
            }
        }
        foreach ($statehistories as $statehistory) {
            $StatehistoryHost = new StatehistoryHost($statehistory['StatehistoryHost']);
            $statehistoryRecords[] = $StatehistoryHost;
        }

        $StatehistorySerializer = new StatehistorySerializer($statehistoryRecords, $UserTime, $end, 'host');
        $this->set('statehistory', $StatehistorySerializer->serialize());
        unset($StatehistorySerializer, $statehistoryRecords);


        //Query downtime records for hosts
        $DowntimeHostConditions = new DowntimeHostConditions();
        $DowntimeHostConditions->setOrder(['DowntimeHost.scheduled_start_time' => 'asc']);
        $DowntimeHostConditions->setFrom($start);
        $DowntimeHostConditions->setTo($end);
        $DowntimeHostConditions->setHostUuid($hostUuid);
        $DowntimeHostConditions->setIncludeCancelledDowntimes(true);


        $query = $this->DowntimeHost->getQueryForReporting($DowntimeHostConditions);
        $downtimes = $this->DowntimeHost->find('all', $query);
        $downtimeRecords = [];
        foreach ($downtimes as $downtime) {
            $downtimeRecords[] = new Downtime($downtime['DowntimeHost']);
        }

        $DowntimeSerializer = new DowntimeSerializer($downtimeRecords, $UserTime);
        $this->set('downtimes', $DowntimeSerializer->serialize());
        unset($DowntimeSerializer, $downtimeRecords);


        $Conditions = new HostNotificationConditions();
        $Conditions->setUseLimit(false);
        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);
        $query = $this->NotificationHost->getQuery($Conditions, []);

        $notificationRecords = [];
        foreach ($this->NotificationHost->find('all', $query) as $notification) {
            $notificationRecords[] = [
                'NotificationHost' => new itnovum\openITCOCKPIT\Core\Views\NotificationHost($notification),
                'Command'          => new itnovum\openITCOCKPIT\Core\Views\Command($notification['Command']),
                'Contact'          => new itnovum\openITCOCKPIT\Core\Views\Contact($notification['Contact'])
            ];
        }

        $NotificationSerializer = new NotificationSerializer($notificationRecords, $UserTime, 'host');
        $this->set('notifications', $NotificationSerializer->serialize());
        unset($NotificationSerializer, $notificationRecords);


        //Process conditions
        $Conditions = new AcknowledgedHostConditions();
        $Conditions->setUseLimit(false);
        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);

        $acknowledgementRecords = [];
        $query = $this->AcknowledgedHost->getQuery($Conditions, []);
        foreach ($this->AcknowledgedHost->find('all', $query) as $acknowledgement) {
            $acknowledgementRecords[] = new itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost($acknowledgement['AcknowledgedHost']);
        }

        $AcknowledgementSerializer = new AcknowledgementSerializer($acknowledgementRecords, $UserTime);
        $this->set('acknowledgements', $AcknowledgementSerializer->serialize());

        $this->set('start', $start);
        $this->set('end', $end);
        $this->viewBuilder()->setOption('serialize', [
            'start',
            'end',
            'groups',
            'statehistory',
            'downtimes',
            'notifications',
            'acknowledgements',
            'timeranges'
        ]);
    }

    /**
     * @deprecated
     */
    public function getGrafanaIframeUrlForDatepicker() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $hostUuid = $this->request->getQuery('uuid');
        $timerange = $this->request->getQuery('from');
        if ($timerange === null) {
            $timerange = 'now-6h';
        }
        $refresh = $this->request->getQuery('refresh');
        if ($refresh === null) {
            $refresh = 0;
        }

        $grafanaDashboard = null;
        $GrafanaDashboardExists = false;

        $ModuleManager = new ModuleManager('GrafanaModule');
        if ($ModuleManager->moduleExists()) {
            $this->loadModel('GrafanaModule.GrafanaDashboard');
            $this->loadModel('GrafanaModule.GrafanaConfiguration');
            $grafanaConfiguration = $this->GrafanaConfiguration->find('first');
            if (!empty($grafanaConfiguration) && $this->GrafanaDashboard->existsForUuid($hostUuid)) {
                $GrafanaDashboardExists = true;
                $dashboardFromDatabase = $this->GrafanaDashboard->find('first', [
                    'conditions' => [
                        'GrafanaDashboard.host_uuid' => $hostUuid
                    ]
                ]);

                $GrafanaConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
                $GrafanaConfiguration->setHostUuid($hostUuid);
                if (isset($dashboardFromDatabase['GrafanaDashboard']['grafana_uid'])) {
                    $GrafanaConfiguration->setGrafanaUid($dashboardFromDatabase['GrafanaDashboard']['grafana_uid']);
                }
                $this->set('iframeUrl', $GrafanaConfiguration->getIframeUrlForDatepicker($timerange, $refresh));
            }
        }

        $this->set('GrafanaDashboardExists', $GrafanaDashboardExists);
        $this->viewBuilder()->setOption('serialize', ['GrafanaDashboardExists', 'iframeUrl']);
    }


    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }

    public function loadCommands() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        $commands = $CommandsTable->getCommandByTypeAsList(HOSTCHECK_COMMAND);

        $this->set('commands', Api::makeItJavaScriptAble($commands));
        $this->viewBuilder()->setOption('serialize', ['commands']);
    }

    /**
     * @param $containerId
     * @param int $hostId
     * @throws Exception
     */
    public function loadElementsByContainerId($containerId, $hostId = 0) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $hostId = (int)$hostId;
        $hosttemplateType = GENERIC_HOST;

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        if (!$ContainersTable->existsById($containerId)) {
            throw new NotFoundException(__('Invalid container'));
        }

        if ($hostId != 0) {
            try {
                $host = $HostsTable->get($hostId);
                $hosttemplateType = $host->get('host_type');
            } catch (RecordNotFoundException $e) {
                //Ignore error
            }
        }

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId);

        $sharingContainers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        if (isset($sharingContainers[$containerId])) {
            //Remove primary container from result
            unset($sharingContainers[$containerId]);
        }
        $sharingContainers = Api::makeItJavaScriptAble($sharingContainers);

        $hosttemplates = $HosttemplatesTable->getHosttemplatesByContainerId($containerIds, 'list', $hosttemplateType);
        $hosttemplates = Api::makeItJavaScriptAble($hosttemplates);

        $hostgroups = $HostgroupsTable->getHostgroupsByContainerId($containerIds, 'list', 'id');
        $hostgroups = Api::makeItJavaScriptAble($hostgroups);

        $timeperiods = $TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list');
        $timeperiods = Api::makeItJavaScriptAble($timeperiods);
        $checkperiods = $timeperiods;

        $contacts = $ContactsTable->contactsByContainerId($containerIds, 'list');
        $contacts = Api::makeItJavaScriptAble($contacts);

        $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($containerIds, 'list', 'id');
        $contactgroups = Api::makeItJavaScriptAble($contactgroups);

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $Systemsettings->getMasterInstanceName();

        $satellites = [];
        $ModuleManager = new ModuleManager('DistributeModule');
        if ($ModuleManager->moduleExists()) {
            /** @var $SatellitesTable SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

            $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
            $satellites[0] = $masterInstanceName;
        }

        $satellites = Api::makeItJavaScriptAble($satellites);

        $this->set('hosttemplates', $hosttemplates);
        $this->set('hostgroups', $hostgroups);
        $this->set('timeperiods', $timeperiods);
        $this->set('checkperiods', $checkperiods);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);
        $this->set('satellites', $satellites);
        $this->set('sharingContainers', $sharingContainers);

        $this->viewBuilder()->setOption('serialize', [
            'hosttemplates',
            'hostgroups',
            'timeperiods',
            'checkperiods',
            'contacts',
            'contactgroups',
            'satellites',
            'sharingContainers'
        ]);
    }

    /**
     * @param int $hosttemplateId
     */
    public function loadHosttemplate($hosttemplateId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        if (!$HosttemplatesTable->existsById($hosttemplateId)) {
            throw new NotFoundException(__('Invalid host template'));
        }

        $hosttemplate = $HosttemplatesTable->getHosttemplateForEdit($hosttemplateId);


        $this->set('hosttemplate', $hosttemplate);
        $this->viewBuilder()->setOption('serialize', ['hosttemplate']);
    }

    public function runDnsLookup() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $hostname = (string)$this->request->getData('hostname');
        $ipAddress = (string)$this->request->getData('address');

        $result = [
            'hostname' => null,
            'address'  => null
        ];

        if ($hostname !== '') {
            $ip = gethostbyname($hostname);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                $result['address'] = $ip;
                $result['hostname'] = $hostname;
            }
        }

        if ($ipAddress !== '') {
            if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                $fqdn = gethostbyaddr($ipAddress);
                if (strlen($fqdn) > 0 && $fqdn !== $ipAddress) {
                    $result['hostname'] = $fqdn;
                    $result['address'] = $ipAddress;
                }
            }
        }

        $this->set('result', $result);
        $this->viewBuilder()->setOption('serialize', ['result']);
    }

    /**
     * This function is designed to be called if a command gets changed.
     * NOT to get initial values like /hosts/edit/$id.json
     *
     * @param int|null $commandId
     * @param int|null $hostId
     */
    public function loadCommandArguments($commandId = null, $hostId = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var $CommandargumentsTable CommandargumentsTable */
        $CommandargumentsTable = TableRegistry::getTableLocator()->get('Commandarguments');

        if (!$CommandsTable->existsById($commandId)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $hostcommandargumentvalues = [];

        if ($hostId != null) {
            //User passed an hostId, so we are in a non add mode!
            //Check if the host has defined command arguments

            /** @var $HostcommandargumentvaluesTable HostcommandargumentvaluesTable */
            $HostcommandargumentvaluesTable = TableRegistry::getTableLocator()->get('Hostcommandargumentvalues');

            $hostCommandArgumentValues = $HostcommandargumentvaluesTable->getByHostIdAndCommandId($hostId, $commandId);

            foreach ($hostCommandArgumentValues as $hostCommandArgumentValue) {
                $hostcommandargumentvalues[] = [
                    'commandargument_id' => $hostCommandArgumentValue['commandargument_id'],
                    'host_id'            => $hostCommandArgumentValue['host_id'],
                    'value'              => $hostCommandArgumentValue['value'],
                    'commandargument'    => [
                        'name'       => $hostCommandArgumentValue['commandargument']['name'],
                        'human_name' => $hostCommandArgumentValue['commandargument']['human_name'],
                        'command_id' => $hostCommandArgumentValue['commandargument']['command_id'],
                    ]
                ];
            }
        }

        //Get command arguments
        $commandarguments = $CommandargumentsTable->getByCommandId($commandId);
        if (empty($hostcommandargumentvalues)) {
            //Host has no command arguments defined
            //Or we are in /hosts/add ?

            //Load command arguments of the check command
            foreach ($commandarguments as $commandargument) {
                $hostcommandargumentvalues[] = [
                    'commandargument_id' => $commandargument['Commandargument']['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['Commandargument']['name'],
                        'human_name' => $commandargument['Commandargument']['human_name'],
                        'command_id' => $commandargument['Commandargument']['command_id'],
                    ]
                ];
            }
        }

        // Merge new command arguments that are missing in the host to host command arguments
        // and remove old command arguments that don't exists in the command anymore.
        $filteredCommandArgumentsValules = [];
        foreach ($commandarguments as $commandargument) {
            $valueExists = false;
            foreach ($hostcommandargumentvalues as $hostcommandargumentvalue) {
                if ($commandargument['Commandargument']['id'] === $hostcommandargumentvalue['commandargument_id']) {
                    $filteredCommandArgumentsValules[] = $hostcommandargumentvalue;
                    $valueExists = true;
                }
            }
            if (!$valueExists) {
                $filteredCommandArgumentsValules[] = [
                    'commandargument_id' => $commandargument['Commandargument']['id'],
                    'value'              => '',
                    'commandargument'    => [
                        'name'       => $commandargument['Commandargument']['name'],
                        'human_name' => $commandargument['Commandargument']['human_name'],
                        'command_id' => $commandargument['Commandargument']['command_id'],
                    ]
                ];
            }
        }
        $hostcommandargumentvalues = $filteredCommandArgumentsValules;


        $this->set('hostcommandargumentvalues', $hostcommandargumentvalues);
        $this->viewBuilder()->setOption('serialize', ['hostcommandargumentvalues']);
    }

    public function loadParentHostsByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $selected = $this->request->getQuery('selected');
        $hostId = $this->request->getQuery('hostId');
        $containerId = $this->request->getQuery('containerId');
        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }
        $HostFilter = new HostFilter($this->request);
        $HostCondition = new HostConditions($HostFilter->ajaxFilter());

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostCondition->setContainerIds($containerIds);
        if (!empty($hostId)) {
            if (!is_array($hostId)) {
                $hostId = [$hostId];
            }
            $HostCondition->setNotConditions([
                'Hosts.id IN' => $hostId
            ]);
        }
        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    /**
     * @param bool $onlyHostsWithWritePermission
     */
    public function loadHostsByString($onlyHostsWithWritePermission = false) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');
        $includeDisabled = $this->request->getQuery('includeDisabled') === 'true';

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostFilter = new HostFilter($this->request);

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setIncludeDisabled($includeDisabled);
        $HostCondition->setContainerIds($this->MY_RIGHTS);
        if ($onlyHostsWithWritePermission) {
            $writeContainers = [];
            foreach ($this->MY_RIGHTS_LEVEL as $containerId => $rightLevel) {
                $rightLevel = (int)$rightLevel;
                if ($rightLevel === WRITE_RIGHT) {
                    $writeContainers[$containerId] = $rightLevel;
                }
            }
            $HostCondition->setContainerIds(array_keys($writeContainers));
        }

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    public function loadHostsByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->getQuery('containerId');
        $selected = $this->request->getQuery('selected');

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostFilter = new HostFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        }

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerIds);

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

}
