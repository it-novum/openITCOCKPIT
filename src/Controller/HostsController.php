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
use App\Lib\Interfaces\AcknowledgementHostsTableInterface;
use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Traits\PluginManagerTableTrait;
use App\Model\Entity\Changelog;
use App\Model\Entity\Service;
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
use App\Model\Table\MacrosTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplategroupsTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Core\Plugin;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use DistributeModule\Model\Table\SatellitesTable;
use ImportModule\Model\Table\ImportedHostsTable;
use itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\CommandArgReplacer;
use itnovum\openITCOCKPIT\Core\Comparison\HostComparisonForSave;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\CustomMacroReplacer;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostControllerRequest;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\HostNotificationConditions;
use itnovum\openITCOCKPIT\Core\HostSharingPermissions;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\Merger\HostMergerForBrowser;
use itnovum\openITCOCKPIT\Core\Merger\HostMergerForView;
use itnovum\openITCOCKPIT\Core\Merger\ServiceMergerForView;
use itnovum\openITCOCKPIT\Core\Permissions\HostContainersPermissions;
use itnovum\openITCOCKPIT\Core\Reports\DaterangesCreator;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\StatehistoryHostConditions;
use itnovum\openITCOCKPIT\Core\Timeline\AcknowledgementSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\DowntimeSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\Groups;
use itnovum\openITCOCKPIT\Core\Timeline\NotificationSerializer;
use itnovum\openITCOCKPIT\Core\Timeline\StatehistorySerializer;
use itnovum\openITCOCKPIT\Core\Timeline\TimeRangeSerializer;
use itnovum\openITCOCKPIT\Core\UserDefinedMacroReplacer;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementHost;
use itnovum\openITCOCKPIT\Core\Views\BBCodeParser;
use itnovum\openITCOCKPIT\Core\Views\Command;
use itnovum\openITCOCKPIT\Core\Views\Contact;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Core\Views\Downtime;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Hosttemplate;
use itnovum\openITCOCKPIT\Core\Views\NotificationHost;
use itnovum\openITCOCKPIT\Core\Views\ServiceStateSummary;
use itnovum\openITCOCKPIT\Core\Views\StatehistoryHost;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;


/**
 * Class HostsController
 * @package App\Controller
 */
class HostsController extends AppController {

    use PluginManagerTableTrait;

    public function index() {
        /** @var User $User */
        $User = new User($this->getUser());

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();

        $satellites = [];

        if (Plugin::isLoaded('DistributeModule')) {
            /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

            $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
            $satellites[0] = $masterInstanceName;
        }

        if (!$this->isApiRequest()) {
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
        $ServicestatusTable = $this->DbBackend->getServicestatusTable();

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if ($this->DbBackend->isNdoUtils()) {
            $hosts = $HostsTable->getHostsIndex($HostFilter, $HostCondition, $PaginateOMat);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
            //$query = $this->Hoststatus->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            //$modelName = 'Hoststatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $hosts = $HostsTable->getHostsIndexStatusengine3($HostFilter, $HostCondition, $PaginateOMat);
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
                ->all()
                ->toList();

            $servicestatus = $ServicestatusTable->byUuids($serviceUuids, $ServicestatusFields);
            $ServicestatusObjects = Servicestatus::fromServicestatusByUuid($servicestatus);
            $serviceStateSummary = ServiceStateSummary::getServiceStateSummary($ServicestatusObjects, false);

            $serviceStateSummary['state'] = array_combine(
                [
                    'ok',
                    'warning',
                    'critical',
                    'unknown'
                ],
                $serviceStateSummary['state']
            );
            $Host = new Host($host);
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

            if ($Host->isSatelliteHost() && isset($satellites[$Host->getSatelliteId()])) {
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
        $this->viewBuilder()->setOption('serialize', ['all_hosts']);
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

        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        $hoststatus = $HoststatusTable->byUuid($host->get('uuid'), $HoststatusFields);

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

    /**
     * @param $uuid
     */
    public function byUuid($uuid) {
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        try {
            $host = $HostsTable->getHostByUuid($uuid);

            if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
                $this->render403();
                return;
            }
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('Host not found');
        }

        $this->set('host', $host);
        $this->viewBuilder()->setOption('serialize', ['host']);
    }

    /**
     * @throws MissingDbBackendException
     */
    public function notMonitored() {
        /** @var SystemsettingsTable $Systemsettings */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $masterInstanceName = $Systemsettings->getMasterInstanceName();
        $SatelliteNames = [];
        if (Plugin::isLoaded('DistributeModule')) {
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
            $hosts = $HostsTable->getHostsNotMonitoredStatusengine3($HostFilter, $HostCondition, $PaginateOMat);
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

            $saveHostAndAssignMatchingServicetemplateGroups = $this->request->getData('save_host_and_assign_matching_servicetemplate_groups', false) === true;

            $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($hosttemplateId);
            $HostComparisonForSave = new HostComparisonForSave($this->request->getData(), $hosttemplate, true);
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

                if ($saveHostAndAssignMatchingServicetemplateGroups === true) {
                    /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
                    $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

                    $result = $ServicetemplategroupsTable->assignMatchingServicetemplategroupsByHostgroupsToHost(
                        $requestData['Host']['hostgroups']['_ids'],
                        $host->get('id'),
                        $User->getId(),
                        $this->MY_RIGHTS
                    );

                    if ($this->isJsonRequest()) {
                        $this->set('id', $host->get('id'));
                        $this->set('services', ['_ids' => $result['newServiceIds']]);
                        $this->set('errors', $result['errors']);
                        $this->set('servicetemplategroups_removed_count', $result['servicetemplategroups_removed_count']);
                        $this->viewBuilder()->setOption('serialize', ['id', 'services', 'errors', 'servicetemplategroups_removed_count']);
                        return;
                    }

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

    /**
     * @param null $id
     */
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
        $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($host['Host']['hosttemplate_id']);
        $HostMergerForView = new HostMergerForView($host, $hosttemplate);
        $mergedHost = $HostMergerForView->getDataForView();
        $hostForChangelog = $mergedHost;

        $oldSharingContainers = $hostForChangelog['Host']['hosts_to_containers_sharing']['_ids'];

        if (!$this->allowedByContainerId($host['Host']['hosts_to_containers_sharing']['_ids'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return host information
            $commands = $CommandsTable->getCommandByTypeAsList(HOSTCHECK_COMMAND);

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

            $typesForView = $HostsTable->getHostTypesWithStyles();
            $hostType = $typesForView[$mergedHost['Host']['host_type']];

            $this->set('commands', Api::makeItJavaScriptAble($commands));
            $this->set('host', $mergedHost);
            $this->set('hosttemplate', $hosttemplate);
            $this->set('isPrimaryContainerChangeable', $HostContainersPermissions->isPrimaryContainerChangeable());
            $this->set('allowSharing', $HostContainersPermissions->allowSharing($this->MY_RIGHTS, $host['Host']['host_type']));
            $this->set('isHostOnlyEditableDueToHostSharing', $isHostOnlyEditableDueToHostSharing);
            $this->set('fakeDisplayContainers', Api::makeItJavaScriptAble($fakeDisplayContainers));
            $this->set('areContactsInheritedFromHosttemplate', $HostMergerForView->areContactsInheritedFromHosttemplate());
            $this->set('hostType', $hostType);

            $this->viewBuilder()->setOption('serialize', [
                'host',
                'commands',
                'hosttemplate',
                'isPrimaryContainerChangeable',
                'allowSharing',
                'isHostOnlyEditableDueToHostSharing',
                'fakeDisplayContainers',
                'areContactsInheritedFromHosttemplate',
                'hostType'
            ]);
            return;
        }


        if ($this->request->is('post')) {
            $hosttemplateId = $this->request->getData('Host.hosttemplate_id');
            if ($hosttemplateId === null) {
                throw new \RuntimeException('Hosttemplate id needs to set.');
            }
            if (!$HosttemplatesTable->existsById($hosttemplateId)) {
                throw new NotFoundException(__('Invalid host template'));
            }
            $User = new User($this->getUser());
            $saveHostAndAssignMatchingServicetemplateGroups = $this->request->getData('save_host_and_assign_matching_servicetemplate_groups', false) === true;

            $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($hosttemplateId);

            $HostContainersPermissions = new HostContainersPermissions(
                $host['Host']['container_id'],
                $host['Host']['hosts_to_containers_sharing']['_ids'],
                $this->getWriteContainers(),
                $this->hasRootPrivileges
            );
            $requestData = $this->request->getData();

            $newSharingContainers = array_merge(
                $requestData['Host']['hosts_to_containers_sharing']['_ids'],
                [$requestData['Host']['container_id']]
            );

            $removedSharingContainers = array_diff($oldSharingContainers, $newSharingContainers);

            if (!empty($removedSharingContainers)) {
                //update dependent service groups and remove services if permissions has been gone
                /** @var ServicesTable $ServicesTable */
                $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                $ServicesTable->_cleanupServicesByHostIdAndRemovedContainerIds($id, $removedSharingContainers, $User->getId());
            }

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

                if ($saveHostAndAssignMatchingServicetemplateGroups === true) {
                    /** @var $ServicetemplategroupsTable ServicetemplategroupsTable */
                    $ServicetemplategroupsTable = TableRegistry::getTableLocator()->get('Servicetemplategroups');

                    $resultForAssign = $ServicetemplategroupsTable->assignMatchingServicetemplategroupsByHostgroupsToHost(
                        $requestData['Host']['hostgroups']['_ids'],
                        $hostEntity->get('id'),
                        $User->getId(),
                        $this->MY_RIGHTS
                    );


                    $resultForDisable = $ServicetemplategroupsTable->disableServicesIfMatchingHostgroupsHasBeenRemoved(
                        $hostEntity->get('id'),
                        $User->getId(),
                        $mergedHost['Host']['hostgroups']['_ids'],
                        $requestData['Host']['hostgroups']['_ids']
                    );

                    if ($this->isJsonRequest()) {
                        $this->set('id', $hostEntity->get('id'));
                        $this->set('services', ['_ids' => $resultForAssign['newServiceIds']]);
                        $this->set('disabled_services', ['_ids' => $resultForDisable['disabledServiceIds']]);
                        $this->set('errors', $resultForAssign['errors']);
                        $this->set('disabled_errors', $resultForDisable['errors']);
                        $this->set('servicetemplategroups_removed_count', $resultForAssign['servicetemplategroups_removed_count']);
                        $this->set('services_disabled_count', $resultForDisable['services_disabled_count']);
                        $this->viewBuilder()->setOption('serialize', [
                            'id',
                            'services',
                            'disabled_services',
                            'errors',
                            'disabled_errors',
                            'servicetemplategroups_removed_count',
                            'services_disabled_count'
                        ]);
                        return;
                    }

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

    public function edit_details($host_id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }
        $User = new User($this->getUser());
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        /** @var $ContactgroupsTable ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');

        if ($this->request->is('get')) {
            $hosts = $HostsTable->getHostsForEditDetails(func_get_args());

            /** @var $ContainersTable ContainersTable */
            $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

            $contacts = $ContactsTable->contactsByContainerId($this->MY_RIGHTS, 'list');
            $contactgroups = $ContactgroupsTable->getContactgroupsByContainerId($this->MY_RIGHTS, 'list', 'id');

            //get sharing containers
            $sharingContainers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);

            $satellites = [];
            if (Plugin::isLoaded('DistributeModule')) {
                /** @var SystemsettingsTable $SystemsettingsTable */
                $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
                $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();

                /** @var $SatellitesTable SatellitesTable */
                $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

                $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
                $satellites[0] = $masterInstanceName;
            }
            $satellites = Api::makeItJavaScriptAble($satellites);

            $this->set('hosts', $hosts);
            $this->set('contacts', Api::makeItJavaScriptAble($contacts));
            $this->set('contactgroups', Api::makeItJavaScriptAble($contactgroups));
            $this->set('satellites', $satellites);

            $this->set('sharingContainers', Api::makeItJavaScriptAble($sharingContainers));
            $this->viewBuilder()->setOption('serialize', [
                    'hosts',
                    'contacts',
                    'contactgroups',
                    'sharingContainers',
                    'satellites'
                ]
            );
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        if ($this->request->is('post') || $this->request->is('put')) {
            $hostIds = $this->request->getData('data.hosts', []);
            $detailsToEdit = $this->request->getData('data.details', []);
            foreach ($hostIds as $hostId) {
                $dataToSave = [];
                $sharedContainers = [];
                $hostObject = $HostsTable->getHostByIdForEditDetails($hostId);
                $hostObjectForChangelog = ['Host' => $hostObject->toArray()];
                $containerIdsForChangelog = [];
                $primaryContainerId = $hostObject->get('container_id');
                foreach ($hostObject->get('hosts_to_containers_sharing') as $container) {
                    $containerId = $container->get('id');
                    $containerIdsForChangelog[] = $containerId;
                    if ($primaryContainerId !== $containerId) {
                        $sharedContainers[] = $containerId;
                    }
                }
                $hostSharingPermissions = new HostSharingPermissions(
                    $primaryContainerId,
                    $this->hasRootPrivileges,
                    $sharedContainers,
                    $this->MY_RIGHTS
                );


                $allowSharing = $hostSharingPermissions->allowSharing();
                if ($allowSharing) {
                    $hostData = $hostObject->toArray();
                    $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($hostData['hosttemplate_id']);
                    $HostMergerForView = new HostMergerForView(['Host' => $hostData], $hosttemplate);

                    $mergedHost = $HostMergerForView->getDataForView();
                    $mergedHost['Host']['contacts'] = $hostData['contacts'];
                    $mergedHost['Host']['contactgroups'] = $hostData['contactgroups'];

                    if ($detailsToEdit['editSharedContainers'] == 1) {
                        if (!empty($detailsToEdit['Host']['hosts_to_containers_sharing']['_ids'])) {
                            if ($detailsToEdit['keepSharedContainers'] == 1) {
                                $containerIds = array_merge(
                                    $sharedContainers,
                                    $detailsToEdit['Host']['hosts_to_containers_sharing']['_ids']
                                );
                                $containerIds[] = $primaryContainerId;

                            } else {
                                $containerIds = $detailsToEdit['Host']['hosts_to_containers_sharing']['_ids'];
                                $containerIds[] = $primaryContainerId;

                            }
                            $containerIds = array_unique($containerIds);

                            $dataToSave['hosts_to_containers_sharing'] = [
                                '_ids' => $containerIds
                            ];

                            $containerIdsForChangelog = $containerIds;
                        }
                    }

                    if ($detailsToEdit['editDescription'] == 1) {
                        $newDescription = $detailsToEdit['Host']['description'];
                        if (!empty($newDescription) && $newDescription != $mergedHost['Host']['description']) {
                            $dataToSave['description'] = $newDescription;
                        }
                    }
                    if ($detailsToEdit['editTags'] == 1) {
                        $newTags = $detailsToEdit['Host']['tags'];
                        if (!empty($newTags) && $newTags != $mergedHost['Host']['tags']) {
                            $dataToSave['tags'] = $newTags;
                        }
                    }
                    if ($detailsToEdit['editPriority'] == 1) {
                        $newPriority = $detailsToEdit['Host']['priority'];
                        if (!empty($newPriority) && $newPriority != $mergedHost['Host']['priority']) {
                            $dataToSave['priority'] = $newPriority;
                        }
                    }
                    if ($detailsToEdit['editCheckInterval'] == 1) {
                        $newCheckInterval = $detailsToEdit['Host']['check_interval'];
                        if (!empty($newCheckInterval) && $newCheckInterval != $mergedHost['Host']['check_interval']) {
                            $dataToSave['check_interval'] = $newCheckInterval;
                        }
                    }
                    if ($detailsToEdit['editRetryInterval'] == 1) {
                        $newRetryInterval = $detailsToEdit['Host']['retry_interval'];
                        if (!empty($newRetryInterval) && $newRetryInterval != $mergedHost['Host']['retry_interval']) {
                            $dataToSave['retry_interval'] = $newRetryInterval;
                        }
                    }
                    if ($detailsToEdit['editMaxNumberOfCheckAttempts'] == 1) {
                        $newMaxNumberOfCheckAttempts = $detailsToEdit['Host']['max_check_attempts'];
                        if (!empty($newMaxNumberOfCheckAttempts) && $newMaxNumberOfCheckAttempts != $mergedHost['Host']['max_check_attempts']) {
                            $dataToSave['max_check_attempts'] = $newMaxNumberOfCheckAttempts;
                        }
                    }
                    if ($detailsToEdit['editNotificationInterval'] == 1) {
                        $newNotificationInterval = $detailsToEdit['Host']['notification_interval'];
                        if (!empty($newNotificationInterval) && $newNotificationInterval != $mergedHost['Host']['notification_interval']) {
                            $dataToSave['notification_interval'] = $newNotificationInterval;
                        }
                    }
                    if ($detailsToEdit['editContacts'] == 1) {
                        $newContacts = $detailsToEdit['Host']['contacts']['_ids'];
                        $allContactsAreVisibleForUser = false;
                        $contactsFromHost = [];
                        if (!empty($newContacts)) {
                            //Check user permissions for already exists contacts. Are all existing contacts are visible for user
                            if (!empty($mergedHost['Host']['contacts']) || !empty($mergedHost['Host']['hosttemplate']['contacts'])) {
                                $contactsFromHost = $mergedHost['Host']['contacts'];
                                if (empty($contactsFromHost)) {
                                    $contactsFromHost = $mergedHost['Host']['hosttemplate']['contacts'];
                                }
                                if (!empty($contactsFromHost)) {
                                    foreach ($contactsFromHost as $contact) {
                                        $contactContainerIds = Hash::extract($contact['containers'], '{n}.id');
                                        if (empty(array_intersect($contactContainerIds, $this->MY_RIGHTS))) {
                                            break;
                                        }
                                    }
                                    $allContactsAreVisibleForUser = true;
                                } else {
                                    $allContactsAreVisibleForUser = true; //nothing to do
                                }
                            }
                            if ($allContactsAreVisibleForUser === true) {
                                //Container permissions check for contacts
                                // Host can use this contacts
                                // Check if the contacts can be used by the host
                                $contactsAfterContainerCheck = $ContactsTable->removeContactsWhichAreNotInContainer(
                                    $newContacts,
                                    $mergedHost['Host']['container_id']
                                );
                                if (empty(array_diff($newContacts, $contactsAfterContainerCheck))) {
                                    if ($detailsToEdit['keepContacts']) {
                                        $dataToSave['contacts'] = [
                                            '_ids' => array_unique(
                                                array_merge(
                                                    $contactsFromHost, $newContacts
                                                )
                                            )
                                        ];
                                    } else {
                                        $dataToSave['contacts'] = [
                                            '_ids' => $newContacts

                                        ];
                                    }
                                    $dataToSave['own_contacts'] = 1;
                                }
                            }
                        }
                    }
                    if ($detailsToEdit['editContactgroups'] == 1) {
                        $newContactgroups = $detailsToEdit['Host']['contactgroups']['_ids'];
                        $contactgroupsFromHost = [];
                        $allContactGroupsAreVisibleForUser = false;
                        if (!empty($newContacts)) {
                            //Check user permissions for already exists contacts. Are all existing contact groups are visible for user
                            if (!empty($mergedHost['Host']['contactgroups']) || !empty($mergedHost['Host']['hosttemplate']['contactgroups'])) {
                                $contactgroupsFromHost = $mergedHost['Host']['contactgroups'];
                                if (empty($contactgroupsFromHost)) {
                                    $contactgroupsFromHost = $mergedHost['Host']['hosttemplate']['contactgroups'];
                                }
                                if (!empty($contactgroupsFromHost)) {
                                    foreach ($contactgroupsFromHost as $contactgroup) {
                                        $contactgroupContainerIds = Hash::extract($contactgroup['container'], '{n}.parent_id');
                                        if (empty(array_intersect($contactgroupContainerIds, $this->MY_RIGHTS))) {
                                            break;
                                        }
                                    }
                                    $allContactGroupsAreVisibleForUser = true;
                                } else {
                                    $allContactGroupsAreVisibleForUser = true; //nothing to do
                                }
                            }
                            if ($allContactGroupsAreVisibleForUser === true) {
                                // Container permissions check for contact groups
                                // Host can use this contact groups
                                // Check if the contact groups can be used by the host
                                $contactgroupssAfterContainerCheck = $ContactgroupsTable->removeContactgroupsWhichAreNotInContainer(
                                    $newContactgroups,
                                    $mergedHost['Host']['container_id']
                                );
                                if (empty(array_diff($newContactgroups, $contactgroupssAfterContainerCheck))) {
                                    if ($detailsToEdit['keepContactgroups']) {
                                        $dataToSave['contactgroups'] = [
                                            '_ids' => array_unique(
                                                array_merge(
                                                    $contactgroupsFromHost, $newContactgroups
                                                )
                                            )
                                        ];

                                    } else {
                                        $dataToSave['contactgroups'] = [
                                            '_ids' => $newContactgroups

                                        ];
                                    }
                                    $dataToSave['own_contactgroups'] = 1;
                                }
                            }
                        }
                    }

                    if ($detailsToEdit['editHostUrl'] == 1) {
                        $newHostUrl = $detailsToEdit['Host']['host_url'];
                        if (!empty($newHostUrl) && $newHostUrl != $mergedHost['Host']['host_url']) {
                            $dataToSave['host_url'] = $newHostUrl;
                        }
                    }
                    if ($detailsToEdit['editNotes'] == 1) {
                        $newNotes = $detailsToEdit['Host']['notes'];
                        if (!empty($newNotes) && $newNotes != $mergedHost['Host']['notes']) {
                            $dataToSave['notes'] = $newNotes;
                        }
                    }
                    if ($detailsToEdit['editSatellites'] == 1) {
                        if ($mergedHost['Host']['host_type'] !== EVK_HOST) {
                            $newSatelliteId = $detailsToEdit['Host']['satellite_id'];
                            if (is_numeric($newSatelliteId) && $newSatelliteId != $mergedHost['Host']['satellite_id']) {
                                $dataToSave['satellite_id'] = $newSatelliteId;
                            }
                        }
                    }

                    if (!empty($dataToSave)) {
                        $hostObject = $HostsTable->patchEntity($hostObject, $dataToSave);
                        $HostsTable->save($hostObject);
                        if (!$hostObject->hasErrors()) {
                            /** @var  ChangelogsTable $ChangelogsTable */
                            $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');
                            $changelog_data = $ChangelogsTable->parseDataForChangelog(
                                'edit',
                                'hosts',
                                $hostObject->get('id'),
                                OBJECT_HOST,
                                $containerIdsForChangelog,
                                $User->getId(),
                                $hostObject->get('name'),
                                $hostObjectForChangelog,
                                ['Host' => $hostObject->toArray()]
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
        }
    }

    public function disabled() {
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();
        $SatelliteNames = [];
        if (Plugin::isLoaded('DistributeModule')) {
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
     * @param null $id
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
            $User = new User($this->getUser());
            /** @var  ChangelogsTable $ChangelogsTable */
            $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

            $changelog_data = $ChangelogsTable->parseDataForChangelog(
                'deactivate',
                'hosts',
                $id,
                OBJECT_HOST,
                $host->get('container_id'),
                $User->getId(),
                $host->get('name'),
                []
            );
            if ($changelog_data) {
                /** @var Changelog $changelogEntry */
                $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                $ChangelogsTable->save($changelogEntry);
            }

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
     * @param null $id
     */
    public function enable($id = null) {
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
        $host->disabled = 0;

        if ($HostsTable->save($host)) {
            $User = new User($this->getUser());
            /** @var  ChangelogsTable $ChangelogsTable */
            $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

            $changelog_data = $ChangelogsTable->parseDataForChangelog(
                'activate',
                'hosts',
                $id,
                OBJECT_HOST,
                $host->get('container_id'),
                $User->getId(),
                $host->get('name'),
                []
            );
            if ($changelog_data) {
                /** @var Changelog $changelogEntry */
                $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                $ChangelogsTable->save($changelogEntry);
            }

            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');
            $ServicesTable->updateAll([
                'disabled' => 0
            ], [
                'host_id' => $id
            ]);

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

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var \App\Model\Entity\Host $host */
        $host = $HostsTable
            ->find()
            ->contain([
                'HostescalationsHostMemberships',
                'HostdependenciesHostMemberships',
                'HostsToContainersSharing'
            ])
            ->where([
                'Hosts.id' => $id
            ])
            ->firstOrFail();

        if (!$this->allowedByContainerId($host->getContainerIds())) {
            $this->render403();
            return;
        }

        $usedBy = $host->isUsedByModules();
        if (!empty($usedBy)) {
            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting host'));
            $this->set('usedBy', $usedBy);
            $this->viewBuilder()->setOption('serialize', ['success', 'id', 'message', 'usedBy']);
            return;
        }


        $services = $ServicesTable->getServicesByHostIdForDelete($id, true);
        foreach ($services as $service) {
            /** @var Service $service */
            $usedBy = $service->isUsedByModules();
            if (!empty($usedBy)) {
                $this->response = $this->response->withStatus(400);
                $this->set('success', false);
                $this->set('id', $id);
                $this->set('service_id', $service->get('id'));
                $this->set('message', __('Issue while deleting host. Service used by other objects.'));
                $this->set('usedBy', $usedBy);
                $this->viewBuilder()->setOption('serialize', ['success', 'id', 'message', 'usedBy', 'service_id']);
                return;
            }
        }

        //Host + Services of the host are not in use by any Modules an can be deleted.
        $User = new User($this->getUser());

        //Delete services of the host
        foreach ($services as $serviceEntity) {
            $service = $ServicesTable->find()
                ->contain([
                    'ServiceescalationsServiceMemberships',
                    'ServicedependenciesServiceMemberships'
                ])
                ->where([
                    'Services.id' => $serviceEntity->get('id')
                ])
                ->firstOrFail();

            $ServicesTable->__delete($service, $User);
        }

        //Delete the host
        if ($HostsTable->__delete($host, $User)) {
            $this->set('success', true);
            $this->set('message', __('Host successfully deleted'));
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->set('success', false);
        $this->set('message', __('Error while deleting host'));
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    /**
     * @param null $id
     */
    public function copy($id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        $User = new User($this->getUser());
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        if ($this->request->is('get')) {
            $hosts = $HostsTable->getHostsForCopy(func_get_args());
            $this->set('hosts', $hosts);
            $this->viewBuilder()->setOption('serialize', ['hosts']);
            return;
        }
        $hasErrors = false;
        if ($this->request->is('post') || $this->request->is('put')) {
            //We want to save/validate the data and save it
            $postData = $this->request->getData('data');
            foreach ($postData as $index => $host2copyData) {
                $action = 'copy';
                $currentDataForChangelog = [];
                $extDataForChangelog = [];
                if (!$HostsTable->existsById($host2copyData['Source']['id'])) {
                    continue;
                }
                if (!isset($host2copyData['Host']['id'])) {
                    $hostgroupsIds = [];
                    $parenthostsIds = [];
                    $contactsIds = [];
                    $contactgroupsIds = [];
                    $containerIds = [];
                    $hostcommandargumentvalues = [];
                    $customvariables = [];

                    /** @var \App\Model\Entity\Host $sourceHost */
                    $sourceHost = $HostsTable->getHostDetailsForCopy($host2copyData['Source']['id']);
                    $hostDefaultValues = $sourceHost->extract([
                            'command_id',
                            'hosttemplate_id',
                            'container_id',
                            'check_period_id',
                            'notify_period_id',
                            'check_interval',
                            'retry_interval',
                            'max_check_attempts',
                            'notification_interval',
                            'notify_on_recovery',
                            'notify_on_down',
                            'notify_on_unreachable',
                            'notify_on_flapping',
                            'notify_on_downtime',
                            'flap_detection_enabled',
                            'flap_detection_notifications_enabled',
                            'flap_detection_on_up',
                            'flap_detection_on_down',
                            'flap_detection_on_unreachable',
                            'own_contacts',
                            'own_contactgroups',
                            'own_customvariables',
                            'host_type',
                            'notes',
                            'priority',
                            'tags',
                            'active_checks_enabled',
                            'satellite_id',
                            'notifications_enabled'
                        ]
                    );
                    /** @var \App\Model\Entity\Hosttemplate $hosttemplate */
                    $hosttemplate = $HosttemplatesTable->getHosttemplateForDiff($sourceHost->get('hosttemplate_id'));

                    $tmpHost = $HostsTable->newEmptyEntity();
                    $tmpHost->setNew(true);
                    if (!empty($hostDefaultValues)) {
                        $tmpHost->set($hostDefaultValues);
                    }

                    $tmpHost->set('uuid', UUID::v4());
                    $tmpHost->set('usage_flag', 0);  //This host is not used - because it does not exists yet
                    $tmpHost->set('name', $host2copyData['Host']['name']);
                    $tmpHost->set('description', $host2copyData['Host']['description']);
                    $tmpHost->set('address', $host2copyData['Host']['address']);
                    $tmpHost->set('host_url', $host2copyData['Host']['host_url']);
                    foreach ($sourceHost->get('hostgroups') as $hostgroup) {
                        $hostgroupsIds[] = $hostgroup->get('id');
                    }
                    foreach ($sourceHost->get('hosts_to_containers_sharing') as $container) {
                        $containerIds[] = $container->get('id');
                    }
                    foreach ($sourceHost->get('parenthosts') as $parenthost) {
                        $parenthostsIds[] = $parenthost->get('id');
                    }
                    foreach ($sourceHost->get('contacts') as $contact) {
                        $contactsIds[] = $contact->get('id');
                    }
                    foreach ($sourceHost->get('contactgroups') as $contactgroup) {
                        $contactgroupsIds[] = $contactgroup->get('id');
                    }
                    foreach ($sourceHost->get('hostcommandargumentvalues') as $hostcommandargumentvalue) {
                        $hostcommandargumentvalues[] = [
                            'commandargument_id' => $hostcommandargumentvalue->get('commandargument_id'),
                            'value'              => $hostcommandargumentvalue->get('value'),
                        ];
                    }
                    foreach ($sourceHost->get('customvariables') as $customvariable) {
                        $customvariables[] = [
                            'name'          => $customvariable->get('name'),
                            'value'         => $customvariable->get('value'),
                            'objecttype_id' => OBJECT_HOST,
                            'password'      => $customvariable->get('password')
                        ];
                    }

                    $tmpHost->set([
                        'hosts_to_containers_sharing' => [
                            '_ids' => $containerIds
                        ]
                    ]);
                    $tmpHost->set([
                        'hostgroups' => [
                            '_ids' => $hostgroupsIds
                        ]
                    ]);

                    $tmpHost->set([
                        'parenthosts' => [
                            '_ids' => $parenthostsIds
                        ]
                    ]);
                    $tmpHost->set([
                        'contacts' => [
                            '_ids' => $contactsIds
                        ]
                    ]);
                    $tmpHost->set([
                        'contactgroups' => [
                            '_ids' => $contactgroupsIds
                        ]
                    ]);

                    $tmpHost->hostcommandargumentvalues = $hostcommandargumentvalues;
                    $tmpHost->customvariables = $customvariables;


                    $HostMergerForView = new HostMergerForView(['Host' => $tmpHost->toArray()], $hosttemplate);
                    $mergedHost = $HostMergerForView->getDataForView();
                    $extDataForChangelog = $HostsTable->resolveDataForChangelog($mergedHost);
                    $extDataForChangelog = array_merge($mergedHost, $extDataForChangelog);

                    $hostData = $tmpHost->toArray();
                    $hostData['hosttemplate_flap_detection_enabled'] = $hosttemplate['Hosttemplate']['flap_detection_enabled'];
                    $hostData['hosttemplate_flap_detection_on_up'] = $hosttemplate['Hosttemplate']['flap_detection_on_up'];
                    $hostData['hosttemplate_flap_detection_on_down'] = $hosttemplate['Hosttemplate']['flap_detection_on_down'];
                    $hostData['hosttemplate_flap_detection_on_unreachable'] = $hosttemplate['Hosttemplate']['flap_detection_on_unreachable'];
                    $newHost = $HostsTable->newEntity($hostData);

                }

                if (isset($host2copyData['Host']['id'])) {
                    $action = 'edit';
                    $newHost = $HostsTable->get($host2copyData['Host']['id']);
                    $currentDataForChangelog = $newHost->toArray();
                    $newHost->set('hosttemplate_flap_detection_enabled', $newHost->get('flap_detection_enabled'));
                    $newHost->set('hosttemplate_flap_detection_on_up', $newHost->get('flap_detection_on_up'));
                    $newHost->set('hosttemplate_flap_detection_on_down', $newHost->get('flap_detection_on_down'));
                    $newHost->set('hosttemplate_flap_detection_on_unreachable', $newHost->get('flap_detection_on_unreachable'));
                    $newHost = $HostsTable->patchEntity($newHost, $host2copyData['Host']);
                    $extDataForChangelog = $newHost->toArray();
                }

                $HostsTable->save($newHost);
                $postData[$index]['Error'] = [];

                if ($newHost->hasErrors()) {
                    $postData[$index]['Error'] = $newHost->getErrors();
                    $hasErrors = true;
                } else {
                    //No errors
                    $postData[$index]['Host']['id'] = $newHost->get('id');

                    /** @var  ChangelogsTable $ChangelogsTable */
                    $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');

                    $changelog_data = $ChangelogsTable->parseDataForChangelog(
                        $action,
                        'hosts',
                        $newHost->get('id'),
                        OBJECT_HOST,
                        $containerIds,
                        $User->getId(),
                        $newHost->get('name'),
                        $extDataForChangelog,
                        $currentDataForChangelog
                    );

                    if ($changelog_data) {
                        /** @var Changelog $changelogEntry */
                        $changelogEntry = $ChangelogsTable->newEntity($changelog_data);
                        $ChangelogsTable->save($changelogEntry);
                    }

                    if ($action === 'copy') {
                        $ServicetemplateCache = new KeyValueStore();
                        $ServicetemplateEditCache = new KeyValueStore();
                        $hostId = $newHost->get('id');
                        $hostContactsAndContactgroups = $HostsTable->getContactsAndContactgroupsById($hostId);
                        $hosttemplateContactsAndContactgroups = $HosttemplatesTable->getContactsAndContactgroupsById($newHost->get('hosttemplate_id'));

                        /** @var ServicesTable $ServicesTable */
                        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
                        /** @var  ServicetemplatesTable $ServicetemplatesTable */
                        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

                        $servicesFromHost = $ServicesTable->getServicesByHostIdForCopy($sourceHost->get('id'));
                        foreach ($servicesFromHost as $serviceData) {
                            $sourceServiceId = $serviceData['id'];
                            $sourceService = $ServicesTable->getServiceForEdit($sourceServiceId);
                            if (!$ServicetemplateCache->has($sourceService['Service']['servicetemplate_id'])) {
                                $sourceServiceServicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($sourceService['Service']['servicetemplate_id']);
                                $ServicetemplateCache->set($sourceService['Service']['servicetemplate_id'], $sourceServiceServicetemplate);
                            }
                            $sourceServiceServicetemplate = $ServicetemplateCache->get($sourceService['Service']['servicetemplate_id']);

                            $ServiceMergerForView = new ServiceMergerForView(
                                $sourceService,
                                $sourceServiceServicetemplate
                            );

                            $sourceService = $ServiceMergerForView->getDataForView();

                            //This service is not used - because it does not exists yet
                            $sourceService['Service']['usage_flag'] = 0;
                            unset($sourceService['Service']['id'], $sourceService['Service']['uuid'], $sourceService['Service']['host_id']);


                            foreach ($sourceService['Service']['servicecommandargumentvalues'] as $i => $servicecommandargumentvalues) {
                                unset($sourceService['Service']['servicecommandargumentvalues'][$i]['id']);
                                if (isset($sourceService['Service']['servicecommandargumentvalues'][$i]['service_id'])) {
                                    unset($sourceService['Service']['servicecommandargumentvalues'][$i]['service_id']);
                                }

                                if (isset($sourceService['Service']['servicecommandargumentvalues'][$i]['servicetemplate_id'])) {
                                    unset($sourceService['Service']['servicecommandargumentvalues'][$i]['servicetemplate_id']);
                                }
                            }

                            if (!empty($serviceData['Service']['servicecommandargumentvalues'])) {
                                $serviceData['Service']['servicecommandargumentvalues'] = $sourceService['Service']['servicecommandargumentvalues'];
                            }

                            foreach ($sourceService['Service']['serviceeventcommandargumentvalues'] as $i => $serviceeventcommandargumentvalues) {
                                unset($sourceService['Service']['serviceeventcommandargumentvalues'][$i]['id']);
                                if (isset($sourceService['Service']['serviceeventcommandargumentvalues'][$i]['service_id'])) {
                                    unset($sourceService['Service']['serviceeventcommandargumentvalues'][$i]['service_id']);
                                }

                                if (isset($sourceService['Service']['serviceeventcommandargumentvalues'][$i]['servicetemplate_id'])) {
                                    unset($sourceService['Service']['serviceeventcommandargumentvalues'][$i]['servicetemplate_id']);
                                }
                            }

                            if (!empty($serviceData['Service']['serviceeventcommandargumentvalues'])) {
                                $serviceData['Service']['serviceeventcommandargumentvalues'] = $sourceService['Service']['serviceeventcommandargumentvalues'];
                            }

                            foreach ($sourceService['Service']['customvariables'] as $i => $customvariables) {
                                unset($sourceService['Service']['customvariables'][$i]['id']);
                                if (isset($sourceService['Service']['customvariables'][$i]['object_id'])) {
                                    unset($sourceService['Service']['customvariables'][$i]['object_id']);
                                }
                            }


                            $newServiceData = $sourceService;
                            $newServiceData['Service']['host_id'] = $hostId;
                            // Replace service template values with zero

                            if (!$ServicetemplateEditCache->has($sourceService['Service']['servicetemplate_id'])) {
                                $servicetemplate = $ServicetemplatesTable->getServicetemplateForDiff($sourceService['Service']['servicetemplate_id']);
                                $ServicetemplateEditCache->set($sourceService['Service']['servicetemplate_id'], $servicetemplate);
                            }
                            $servicetemplate = $ServicetemplateEditCache->get($sourceService['Service']['servicetemplate_id']);
                            $serviceName = ($sourceService['Service']['name']) ? $sourceService['Service']['name'] : $servicetemplate['Servicetemplate']['name'];
                            $ServiceComparisonForSave = new ServiceComparisonForSave(
                                $newServiceData,
                                $servicetemplate,
                                $hostContactsAndContactgroups,
                                $hosttemplateContactsAndContactgroups
                            );
                            $serviceData = $ServiceComparisonForSave->getDataForSaveForAllFields();
                            //Add required fields for validation
                            $serviceData['servicetemplate_flap_detection_enabled'] = $servicetemplate['Servicetemplate']['flap_detection_enabled'];
                            $serviceData['servicetemplate_flap_detection_on_ok'] = $servicetemplate['Servicetemplate']['flap_detection_on_ok'];
                            $serviceData['servicetemplate_flap_detection_on_warning'] = $servicetemplate['Servicetemplate']['flap_detection_on_warning'];
                            $serviceData['servicetemplate_flap_detection_on_critical'] = $servicetemplate['Servicetemplate']['flap_detection_on_critical'];
                            $serviceData['servicetemplate_flap_detection_on_unknown'] = $servicetemplate['Servicetemplate']['flap_detection_on_unknown'];
                            //Container permissions check for contacts, contact groups and time periods

                            $serviceData['uuid'] = UUID::v4();
                            $newServiceEntity = $ServicesTable->newEntity($serviceData);

                            $ServicesTable->save($newServiceEntity);
                            if (!$newServiceEntity->hasErrors()) {
                                //No errors
                                /** @var  ChangelogsTable $ChangelogsTable */
                                $ChangelogsTable = TableRegistry::getTableLocator()->get('Changelogs');
                                $extDataForChangelog = $ServicesTable->resolveDataForChangelog($sourceService);

                                $changelog_data = $ChangelogsTable->parseDataForChangelog(
                                    $action,
                                    'services',
                                    $newServiceEntity->get('id'),
                                    OBJECT_SERVICE,
                                    $newHost->get('container_id'),
                                    $User->getId(),
                                    $newHost->get('name') . '/' . $serviceName,
                                    array_merge($sourceService, $extDataForChangelog)
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
            }
        }
        if ($hasErrors) {
            $this->response = $this->response->withStatus(400);
        }
        $this->set('result', $postData);
        $this->viewBuilder()->setOption('serialize', ['result']);
    }

    /**
     * @param int|string|null $idOrUuid
     * @throws MissingDbBackendException
     */
    public function browser($idOrUuid = null) {
        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $canUserSeeCheckCommand = isset($this->PERMISSIONS['hosts']['checkcommand']);

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        if ($this->isHtmlRequest()) {
            //Only ship template

            $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();
            $blurryCommandLine = $SystemsettingsTable->blurCheckCommand();
            $this->set('masterInstanceName', $masterInstanceName);
            $this->set('blurryCommandLine', $blurryCommandLine);
            $this->set('username', $User->getFullName());
            return;
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $id = $idOrUuid;
        if (!is_numeric($idOrUuid)) {
            if (preg_match(UUID::regex(), $idOrUuid)) {
                try {
                    $lookupHost = $HostsTable->getHostByUuid($idOrUuid);
                    $id = $lookupHost->get('id');
                } catch (RecordNotFoundException $e) {
                    throw new NotFoundException(__('Host not found'));
                }
            }
        }
        unset($idOrUuid);

        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var HoststatusTableInterface $HoststatusTable */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        /** @var CommandsTable $CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var TimeperiodsTable $TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var AcknowledgementHostsTableInterface $AcknowledgementHostsTable */
        $AcknowledgementHostsTable = $this->DbBackend->getAcknowledgementHostsTable();
        /** @var DowntimehistoryHostsTableInterface $DowntimehistoryHostsTable */
        $DowntimehistoryHostsTable = $this->DbBackend->getDowntimehistoryHostsTable();

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var DocumentationsTable $DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Host not found'));
        }

        $host = $HostsTable->getHostForBrowser($id);

        //Check permissions
        $containerIdsToCheck = Hash::extract($host, 'hosts_to_containers_sharing.{n}.id');
        $containerIdsToCheck[] = $host['container_id'];

        //Check if user is permitted to see this object
        if (!$this->hasRootPrivileges) {
            if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
                $this->render403();
                return;
            }
        }

        $allowEdit = $this->hasRootPrivileges;
        if ($this->hasRootPrivileges === false) {
            $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
            $allowEdit = $ContainerPermissions->hasPermission();
        }
        $hostObj = new Host($host, $allowEdit);


        //Load containers information
        if (array_key_exists($host['container_id'], $this->MY_RIGHTS_LEVEL)) {
            $mainContainer = $ContainersTable->getTreePathForBrowser($host['container_id'], $this->MY_RIGHTS_LEVEL);
            //Add shared containers
            $sharedContainers = [];
            foreach ($host['hosts_to_containers_sharing'] as $container) {
                if (isset($container['id']) && $container['id'] != $host['container_id']) {
                    $sharedContainers[$container['id']] = $ContainersTable->getTreePathForBrowser($container['id'], $this->MY_RIGHTS_LEVEL);
                    //$sharedContainers[$container['id']] = $ContainersTable->treePath($container['id']);
                }
            }
        } else {
            //The user only see this host via host sharing
            //We need to "fake" a primary container because the user has no permissions to the real
            //primary container
            $mainContainer = $ContainersTable->getFakePrimaryContainerForHostBrowserDisplay(
                Hash::extract($host, 'hosts_to_containers_sharing.{n}.id'),
                $this->MY_RIGHTS,
                $this->MY_RIGHTS_LEVEL
            );
            $sharedContainers = [];
        }


        //Load required data to merge and display inheritance data
        $hosttemplate = $HosttemplatesTable->getHosttemplateForHostBrowser($host['hosttemplate_id']);

        //Merge host and inheritance data
        $HostMergerForBrowser = new HostMergerForBrowser(
            $host,
            $hosttemplate
        );
        $mergedHost = $HostMergerForBrowser->getDataForView();

        $mergedHost['is_satellite_host'] = $hostObj->isSatelliteHost();
        $mergedHost['allowEdit'] = $allowEdit;

        $replacePasswordInObjectMacros = false;
        try {
            $systemsettingsReplacePasswordsEntity = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.REPLACE_PASSWORD_IN_OBJECT_MACROS');
            if ($systemsettingsReplacePasswordsEntity->get('value') === '1') {
                $replacePasswordInObjectMacros = true;
            }
        } catch (RecordNotFoundException $e) {
            // Rocket not found in system settings - do not replace passwords in $_HOSTFOOBAR$ custom variables
        }

        //Replace macros in host url
        $HostMacroReplacer = new HostMacroReplacer($mergedHost);
        $HostCustomMacroReplacer = new CustomMacroReplacer($mergedHost['customvariables'], OBJECT_HOST, $replacePasswordInObjectMacros);
        $mergedHost['host_url_replaced'] =
            $HostMacroReplacer->replaceBasicMacros(          // Replace $HOSTNAME$
                $HostCustomMacroReplacer->replaceAllMacros(  // Replace $_HOSTFOOBAR$
                    $mergedHost['host_url']
                )
            );

        $checkCommand = $CommandsTable->getCommandById($mergedHost['command_id']);
        $checkPeriod = $TimeperiodsTable->getTimeperiodByIdCake4($mergedHost['check_period_id']);
        $notifyPeriod = $TimeperiodsTable->getTimeperiodByIdCake4($mergedHost['notify_period_id']);

        // Replace $ARGn$
        $ArgnReplacer = new CommandArgReplacer($mergedHost['hostcommandargumentvalues']);
        $hostCommandLine = $ArgnReplacer->replace($checkCommand['Command']['command_line']);

        // Replace $_HOSTFOOBAR$
        $hostCommandLine = $HostCustomMacroReplacer->replaceAllMacros($hostCommandLine);

        // Replace $HOSTNAME$
        $hostCommandLine = $HostMacroReplacer->replaceBasicMacros($hostCommandLine);

        // Replace $USERn$ Macros (if enabled)
        try {
            $systemsettingsEntity = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.REPLACE_USER_MACROS');
            if ($systemsettingsEntity->get('value') === '1') {
                /** @var MacrosTable $MacrosTable */
                $MacrosTable = TableRegistry::getTableLocator()->get('Macros');
                $macros = $MacrosTable->getAllMacros();

                $UserMacroReplacer = new UserDefinedMacroReplacer($macros);
                $hostCommandLine = $UserMacroReplacer->replaceMacros($hostCommandLine);
            }
        } catch (RecordNotFoundException $e) {
            // Rocket not found in systemsettings - do not replace $USERn$ macros
        }

        $mergedHost['hostCommandLine'] = $hostCommandLine;

        // Convert interval values for humans
        $mergedHost['checkIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['check_interval']);
        $mergedHost['retryIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['retry_interval']);
        $mergedHost['notificationIntervalHuman'] = $UserTime->secondsInHumanShort($mergedHost['notification_interval']);

        //Check permissions for Contacts
        $contactsWithContainers = [];
        $writeContainers = $this->getWriteContainers();

        foreach ($mergedHost['contacts'] as $key => $contact) {
            $contactsWithContainers[$contact['id']] = [];
            foreach ($contact['containers'] as $container) {
                $contactsWithContainers[$contact['id']][] = $container['id'];
            }

            $mergedHost['contacts'][$key]['allowEdit'] = $this->hasRootPrivileges;
            if ($this->hasRootPrivileges === false) {
                if (!empty(array_intersect($contactsWithContainers[$contact['id']], $writeContainers))) {
                    $mergedHost['contacts'][$key]['allowEdit'] = true;
                }
            }
        }

        //Check permissions for Contact groups
        foreach ($mergedHost['contactgroups'] as $key => $contactgroup) {
            $mergedHost['contactgroups'][$key]['allowEdit'] = $this->isWritableContainer($contactgroup['container']['parent_id']);
        }

        //Load host status
        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->wildcard();

        $hoststatus = $HoststatusTable->byUuid($hostObj->getUuid(), $HoststatusFields);
        if (empty($hoststatus)) {
            //Empty host state for Hoststatus object
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $Hoststatus = new Hoststatus($hoststatus['Hoststatus'], $UserTime);
        $hoststatus = $Hoststatus->toArrayForBrowser();

        //Parse BBCode in long output
        $BBCodeParser = new BBCodeParser();
        $hoststatus['longOutputHtml'] = $BBCodeParser->nagiosNl2br($BBCodeParser->asHtml($Hoststatus->getLongOutput(), true));

        $mergedHost['allowEdit'] = $allowEdit;

        $systemsettingsEntity = $SystemsettingsTable->getSystemsettingByKey('TICKET_SYSTEM.URL');
        $ticketSystem = $systemsettingsEntity->get('value');

        //Check for host acknowledgements and downtimes
        $acknowledgement = [];
        if ($Hoststatus->isAcknowledged()) {
            $acknowledgement = $AcknowledgementHostsTable->byHostUuid($hostObj->getUuid());
            if (!empty($acknowledgement)) {
                $Acknowledgement = new AcknowledgementHost($acknowledgement, $UserTime, $allowEdit);
                $acknowledgement = $Acknowledgement->toArray();

                $ticketDetails = [];
                if (!empty($ticketSystem) && preg_match('/^(Ticket)_?(\d+);?(\d+)/', $Acknowledgement->getCommentData(), $ticketDetails)) {
                    $commentDataHtml = $Acknowledgement->getCommentData();
                    if (isset($ticketDetails[1], $ticketDetails[3], $ticketDetails[2])) {
                        $commentDataHtml = sprintf(
                            '<a href="%s%s" target="_blank">%s %s</a>',
                            $ticketSystem,
                            $ticketDetails[3],
                            $ticketDetails[1],
                            $ticketDetails[2]
                        );
                    }
                } else {
                    $commentDataHtml = $BBCodeParser->asHtml($Acknowledgement->getCommentData(), true);
                }

                $acknowledgement['commentDataHtml'] = $commentDataHtml;
            }
        }

        $downtime = [];
        if ($Hoststatus->isInDowntime()) {
            $downtime = $DowntimehistoryHostsTable->byHostUuid($hostObj->getUuid());
            if (!empty($downtime)) {
                $Downtime = new Downtime($downtime, $allowEdit, $UserTime);
                $downtime = $Downtime->toArray();
            }
        }

        //Load parent hosts and parent host status
        $parenthosts = $host['parenthosts'];
        $ParentHoststatusFields = new HoststatusFields($this->DbBackend);
        $ParentHoststatusFields->currentState()->lastStateChange();
        $parentHostStatusRaw = $HoststatusTable->byUuid(
            Hash::extract($host['parenthosts'], '{n}.uuid'),
            $ParentHoststatusFields
        );
        $parentHostStatus = [];
        foreach ($parentHostStatusRaw as $uuid => $parentHoststatus) {
            $ParentHoststatus = new Hoststatus($parentHoststatus['Hoststatus'], $UserTime);
            $parentHostStatus[$uuid] = $ParentHoststatus->toArrayForBrowser();
        }

        $canSubmitExternalCommands = $this->hasPermission('externalcommands', 'hosts');

        if ($canUserSeeCheckCommand === false) {
            $mergedHost['hostCommandLine'] = 'Removed due to insufficient permissions';
            $mergedHost['hostcommandargumentvalues'] = 'Removed due to insufficient permissions';
            $checkCommand = 'Removed due to insufficient permissions';
        }

        // Set data to fronend
        $this->set('mergedHost', $mergedHost);
        $this->set('docuExists', $DocumentationsTable->existsByUuid($hostObj->getUuid()));
        $this->set('areContactsFromHost', $HostMergerForBrowser->areContactsFromHost());
        $this->set('areContactsInheritedFromHosttemplate', $HostMergerForBrowser->areContactsInheritedFromHosttemplate());
        $this->set('hoststatus', $hoststatus);
        $this->set('mainContainer', $mainContainer);
        $this->set('sharedContainers', $sharedContainers);
        $this->set('parenthosts', $parenthosts);
        $this->set('parentHostStatus', $parentHostStatus);
        $this->set('acknowledgement', $acknowledgement);
        $this->set('downtime', $downtime);
        $this->set('checkCommand', $checkCommand);
        $this->set('checkPeriod', $checkPeriod);
        $this->set('notifyPeriod', $notifyPeriod);
        $this->set('canSubmitExternalCommands', $canSubmitExternalCommands);

        $this->viewBuilder()->setOption('serialize', [
            'mergedHost',
            'docuExists',
            'areContactsFromHost',
            'areContactsInheritedFromHosttemplate',
            'hoststatus',
            'mainContainer',
            'sharedContainers',
            'parenthosts',
            'parentHostStatus',
            'acknowledgement',
            'downtime',
            'checkCommand',
            'checkPeriod',
            'notifyPeriod',
            'canSubmitExternalCommands'
        ]);
    }

    public function listToPdf() {
        $User = new User($this->getUser());

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();

        $satellites = [];
        if (Plugin::isLoaded('DistributeModule')) {
            /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

            $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
            $satellites[0] = $masterInstanceName;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $HostFilter = new HostFilter($this->request);

        $HostControllerRequest = new HostControllerRequest($this->request, $HostFilter);
        $HostCondition = new HostConditions();
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

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if ($this->DbBackend->isNdoUtils()) {
            $hosts = $HostsTable->getHostsIndex($HostFilter, $HostCondition);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
            //$query = $this->Hoststatus->getHostIndexQuery($HostCondition, $HostFilter->indexFilter());
            //$modelName = 'Hoststatus';
        }

        if ($this->DbBackend->isStatusengine3()) {
            $hosts = $HostsTable->getHostsIndexStatusengine3($HostFilter, $HostCondition);
        }


        $all_hosts = [];
        $UserTime = new UserTime($User->getTimezone(), $User->getDateformat());
        foreach ($hosts as $host) {
            $Host = new Host($host['Host']);
            $Hoststatus = new Hoststatus($host['Host']['Hoststatus'], $UserTime);

            $satelliteName = $masterInstanceName;
            $satellite_id = 0;
            if ($Host->isSatelliteHost()) {
                $satelliteName = $satellites[$Host->getSatelliteId()];
                $satellite_id = $Host->getSatelliteId();
            }

            $tmpRecord = [
                'Host'       => $Host->toArray(),
                'Hoststatus' => $Hoststatus
            ];
            $tmpRecord['Host']['satelliteName'] = $satelliteName;
            $tmpRecord['Host']['satelliteId'] = $satellite_id;

            $all_hosts[] = $tmpRecord;
        }

        $this->set('User', $User);
        $this->set('all_hosts', $all_hosts);

        $this->viewBuilder()->setOption(
            'pdfConfig',
            [
                'download' => true,
                'filename' => __('Hosts_') . date('dmY_his') . '.pdf',
            ]
        );
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
     * @param null $id
     */
    public function loadHostById($id = null) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->getHostByIdWithHosttemplate($id);

        $containerIdsToCheck = Hash::extract($host, 'hosts_to_containers_sharing.{n}.id');
        $containerIdsToCheck[] = $host['container_id'];
        if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
            $this->render403();
            return;
        }

        foreach ($host as $key => $value) {
            if ($host[$key] === '' || $host[$key] === null) {
                if (isset($host['hosttemplate'][$key])) {
                    $host[$key] = $host['hosttemplate'][$key];
                }
            }
        }

        $host['is_satellite_host'] = (int)$host['satellite_id'] !== 0;
        $host['allow_edit'] = false;
        if ($this->hasRootPrivileges === true) {
            $host['allow_edit'] = true;
        } else {
            if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $containerIdsToCheck);
                $host['allow_edit'] = $ContainerPermissions->hasPermission();
            }
        }

        unset($host['hosttemplate']);
        $this->set('host', $host);
        $this->viewBuilder()->setOption('serialize', ['host']);
    }

    /**
     * @param string | null $uuid
     * @throws MissingDbBackendException
     */
    public function hoststatus($uuid = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        if (!$uuid) {
            throw new NotFoundException(__('Invalid request parameter'));
        }

        /** @var HoststatusTableInterface $HoststatusTable */
        $HoststatusTable = $this->DbBackend->getHoststatusTable();

        $HoststatusFields = new HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState()
            ->isHardstate()
            ->scheduledDowntimeDepth()
            ->problemHasBeenAcknowledged();
        $hoststatus = $HoststatusTable->byUuid($uuid, $HoststatusFields);
        if (empty($hoststatus)) {
            $hoststatus = [
                'Hoststatus' => []
            ];
        }
        $this->set('hoststatus', $hoststatus);
        $this->viewBuilder()->setOption('serialize', ['hoststatus']);
    }

    /**
     * @param int|null $id
     * @throws MissingDbBackendException
     */
    public function timeline($id = null) {
        $session = $this->request->getSession();
        $session->close();

        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        $host = $HostsTable->getHostByIdForTimeline($id);

        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
            $this->render403();
            return;
        }

        $timeperiodId = $host->get('check_period_id');
        if ($timeperiodId === null || $timeperiodId === '') {
            $timeperiodId = $host->get('hosttemplate')->get('check_period_id');
        }

        /** @var TimeperiodsTable $TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $checkTimePeriod = $TimeperiodsTable->getTimeperiodWithTimerangesById($timeperiodId);

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $Groups = new Groups();
        $this->set('groups', $Groups->serialize(true));


        //Process conditions
        $Conditions = new StatehistoryHostConditions();
        $Conditions->setOrder(['StatehistoryHosts.state_time' => 'asc']);

        $start = $this->request->getQuery('start', -1);
        $end = $this->request->getQuery('end', -1);


        if (!is_numeric($start) || $start < 0) {
            $start = time() - 2 * 24 * 3600;
        }


        if (!is_numeric($end) || $end < 0) {
            $end = time();
        }

        /*************  TIME RANGES *************/
        $timeRanges = DaterangesCreator::createDateRanges(
            $start,
            $end,
            $checkTimePeriod['Timeperiod']['timeperiod_timeranges']
        );

        $TimeRangeSerializer = new TimeRangeSerializer($timeRanges, $UserTime);
        $this->set('timeranges', $TimeRangeSerializer->serialize());
        unset($TimeRangeSerializer, $timeRanges);

        $hostUuid = $host->get('uuid');

        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);
        $Conditions->setUseLimit(false);

        /*************  HOST STATEHISTORY *************/
        $StatehistoryHostsTable = $this->DbBackend->getStatehistoryHostsTable();
        $HoststatusTable = $this->DbBackend->getHoststatusTable();

        //Query state history records for host
        /** @var \Statusengine2Module\Model\Entity\StatehistoryHost[] $statehistoriesHost */
        $statehistories = $StatehistoryHostsTable->getStatehistoryIndex($Conditions);

        $statehistoryRecords = [];

        //Host has no state history record for selected time range
        //Get last available state history record for this host
        $record = $StatehistoryHostsTable->getLastRecord($Conditions);
        if (!empty($record)) {
            $record->set('state_time', $start);
            $StatehistoryHost = new StatehistoryHost($record->toArray());
            $statehistoryRecords[] = $StatehistoryHost;
        }

        if (empty($statehistories) && empty($record)) {
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->currentState()
                ->currentState()
                ->isHardstate()
                ->lastStateChange()
                ->lastHardStateChange();

            $hoststatus = $HoststatusTable->byUuid($hostUuid, $HoststatusFields);
            if (!empty($hoststatus)) {
                $isHardstate = false;
                if (isset($hoststatus['Hoststatus']['state_type'])) {
                    $isHardstate = ($hoststatus['Hoststatus']['state_type']) ? true : false;
                }

                if (isset($hoststatus['Hoststatus']['is_hardstate'])) {
                    $isHardstate = ($hoststatus['Hoststatus']['is_hardstate']) ? true : false;
                }

                $record = [
                    'state_time' => $hoststatus['Hoststatus']['last_state_change'],
                    'state'      => $hoststatus['Hoststatus']['current_state'],
                    'state_type' => $isHardstate,
                ];

                $StatehistoryHost = new StatehistoryHost($record);
                $statehistoryRecords[] = $StatehistoryHost;
            }
        }
        foreach ($statehistories as $statehistory) {
            $StatehistoryHost = new StatehistoryHost($statehistory);
            $statehistoryRecords[] = $StatehistoryHost;
        }

        $StatehistorySerializer = new StatehistorySerializer($statehistoryRecords, $UserTime, $end, 'host');
        $this->set('statehistory', $StatehistorySerializer->serialize());
        unset($StatehistorySerializer, $statehistoryRecords);

        /*************  HOST DOWNTIMES *************/
        $DowntimehistoryHostsTable = $this->DbBackend->getDowntimehistoryHostsTable();

        //Query downtime records for hosts
        $DowntimeHostConditions = new DowntimeHostConditions();
        $DowntimeHostConditions->setOrder(['DowntimeHosts.scheduled_start_time' => 'asc']);
        $DowntimeHostConditions->setFrom($start);
        $DowntimeHostConditions->setTo($end);
        $DowntimeHostConditions->setHostUuid($hostUuid);
        $DowntimeHostConditions->setIncludeCancelledDowntimes(true);


        $downtimes = $DowntimehistoryHostsTable->getDowntimesForReporting($DowntimeHostConditions);
        $downtimeRecords = [];
        foreach ($downtimes as $downtime) {
            $downtimeRecords[] = new Downtime($downtime);
        }

        $DowntimeSerializer = new DowntimeSerializer($downtimeRecords, $UserTime);
        $this->set('downtimes', $DowntimeSerializer->serialize());
        unset($DowntimeSerializer, $downtimeRecords);

        /*************  HOST NOTIFICATIONS *************/
        $NotificationHostsTable = $this->DbBackend->getNotificationHostsTable();

        $Conditions = new HostNotificationConditions();
        $Conditions->setUseLimit(false);
        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);

        $notifications = $NotificationHostsTable->getNotifications($Conditions);
        $notificationRecords = [];
        foreach ($notifications as $notification) {
            $notificationRecords[] = [
                'NotificationHost' => new NotificationHost($notification),
                'Command'          => new Command($notification['Commands']),
                'Contact'          => new Contact($notification['Contacts'])
            ];
        }

        $NotificationSerializer = new NotificationSerializer($notificationRecords, $UserTime, 'host');
        $this->set('notifications', $NotificationSerializer->serialize());
        unset($NotificationSerializer, $notificationRecords);

        /*************  HOST ACKNOWLEDGEMENTS *************/
        $AcknowledgementHostsTable = $this->DbBackend->getAcknowledgementHostsTable();

        //Process conditions
        $Conditions = new AcknowledgedHostConditions();
        $Conditions->setUseLimit(false);
        $Conditions->setFrom($start);
        $Conditions->setTo($end);
        $Conditions->setHostUuid($hostUuid);

        $acknowledgementRecords = [];
        $acknowledgements = $AcknowledgementHostsTable->getAcknowledgements($Conditions);

        foreach ($acknowledgements as $acknowledgement) {
            $acknowledgementRecords[] = new AcknowledgementHost($acknowledgement->toArray());
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

        $GrafanaDashboardExists = false;
        $iframeUrl = null;


        if (Plugin::isLoaded('GrafanaModule')) {
            /** @var \GrafanaModule\Model\Table\GrafanaConfigurationsTable $GrafanaConfigurationsTable */
            $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');

            /** @var \GrafanaModule\Model\Table\GrafanaDashboardsTable $GrafanaDashboardsTable */
            $GrafanaDashboardsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaDashboards');

            $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
            $hasGrafanaConfig = $grafanaConfiguration['api_url'] !== '';
            $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

            if ($GrafanaDashboardsTable->existsForUuid($hostUuid)) {
                $GrafanaDashboardExists = true;
                $dashboard = $GrafanaDashboardsTable->getDashboardByHostUuid($hostUuid);

                $GrafanaApiConfiguration->setHostUuid($hostUuid);
                if ($dashboard->get('grafana_uid')) {
                    $GrafanaApiConfiguration->setGrafanaUid($dashboard->get('grafana_uid'));
                }
                $iframeUrl = $GrafanaApiConfiguration->getIframeUrlForDatepicker($timerange, $refresh);
            }
        }

        $this->set('GrafanaDashboardExists', $GrafanaDashboardExists);
        $this->set('iframeUrl', $iframeUrl);
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
     * @throws \Exception
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

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();

        $satellites = [];
        if (Plugin::isLoaded('DistributeModule')) {
            /** @var $SatellitesTable SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

            $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
            $satellites[0] = $masterInstanceName;
        }
        $satellites = Api::makeItJavaScriptAble($satellites);

        $exporters = [];
        if (Plugin::isLoaded('PrometheusModule')) {
            /** @var \PrometheusModule\Model\Table\PrometheusExportersTable $PrometheusExportersTable */
            $PrometheusExportersTable = TableRegistry::getTableLocator()->get('PrometheusModule.PrometheusExporters');

            $exporters = $PrometheusExportersTable->getExportersByContainerId($containerIds, 'list', 'id');
            $exporters = Api::makeItJavaScriptAble($exporters);
        }

        $this->set('hosttemplates', $hosttemplates);
        $this->set('hostgroups', $hostgroups);
        $this->set('timeperiods', $timeperiods);
        $this->set('checkperiods', $checkperiods);
        $this->set('contacts', $contacts);
        $this->set('contactgroups', $contactgroups);
        $this->set('satellites', $satellites);
        $this->set('sharingContainers', $sharingContainers);
        $this->set('exporters', $exporters);

        $this->viewBuilder()->setOption('serialize', [
            'hosttemplates',
            'hostgroups',
            'timeperiods',
            'checkperiods',
            'contacts',
            'contactgroups',
            'satellites',
            'sharingContainers',
            'exporters'
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
        $hostcommandargumentvalues = Hash::sort(
            $hostcommandargumentvalues,
            '{n}.commandargument.name',
            'asc',
            'natural'
        );

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
            $HostsTable->getHostsForAngular($HostCondition, $selected, true)
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
        $resolveContainerIds = $this->request->getQuery('resolveContainerIds', false);

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $HostFilter = new HostFilter($this->request);

        $containerIds = [ROOT_CONTAINER, $containerId];
        if ($containerId == ROOT_CONTAINER) {
            //Don't panic! Only root users can edit /root objects ;)
            //So no loss of selected hosts/host templates
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds(ROOT_CONTAINER, true);
        } else if ($containerId !== ROOT_CONTAINER && $resolveContainerIds) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerId, true);
            $containerIds = array_merge($containerIds, [ROOT_CONTAINER, $containerId]);
        }

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerIds);

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    public function loadAdditionalInformation() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $id = $this->request->getQuery('id');

        $additionalInformationExists = false;

        if (Plugin::isLoaded('ImportModule')) {
            /** @var ImportedHostsTable $ImportedHostsTable */
            $ImportedHostsTable = TableRegistry::getTableLocator()->get('ImportModule.ImportedHosts');
            $additionalInformationExists = $ImportedHostsTable->existsImportedHostByHostId($id);
        }

        $this->set('AdditionalInformationExists', $additionalInformationExists);
        $this->viewBuilder()->setOption('serialize', ['AdditionalInformationExists']);
    }

    public function checkForDuplicateHostname() {
        if (!$this->isApiRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }


        /** @var $HostTable HostsTable */
        $HostTable = TableRegistry::getTableLocator()->get('Hosts');

        $hostname = $this->request->getData('hostname', '');
        $excludedHostIds = $this->request->getData('excludedHostIds', []);

        $MY_RIGHTS = [];
        if ($this->hasRootPrivileges === false) {
            $MY_RIGHTS = $this->MY_RIGHTS;
        }

        $isHostnameUnique = $HostTable->isHostnameUnique($hostname, $MY_RIGHTS, $excludedHostIds);

        $isHostnameInUse = $isHostnameUnique === false;

        $this->set('isHostnameInUse', $isHostnameInUse);
        $this->viewBuilder()->setOption('serialize', ['isHostnameInUse']);
    }
}
