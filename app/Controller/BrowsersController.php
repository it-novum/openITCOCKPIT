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

//App::import('Model', 'Host');
//App::import('Model', 'Container');
use itnovum\openITCOCKPIT\Core\ModuleManager;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;

/**
 * Class BrowsersController
 * @property Container Container
 */
class BrowsersController extends AppController {

    public $layout = 'angularjs';
    public $helpers = [
        'PieChart',
        'BrowserMisc',
        'Status',
        'Monitoring',
    ];
    public $uses = [
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Host',
        'Service',
        'Container',
        'Tenant',
        'Browser',
    ];

    public $components = [
        'paginator',
    ];

    function index($containerId = null) {
        if ($containerId === null) {
            $containerId = ROOT_CONTAINER;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);


        $top_node = $this->Container->findById(ROOT_CONTAINER);
        $parents = $this->Container->getPath($top_node['Container']['parent_id']);

        $tenants = $this->getTenants();
        natcasesort($tenants);


        $hostQuery = $this->Browser->hostsQuery(ROOT_CONTAINER);
        $serviceQuery = $this->Browser->serviceQuery(ROOT_CONTAINER);
        if ($User->isRecursiveBrowserEnabled()) {
            $hostQuery = $this->Browser->hostsQuery($this->MY_RIGHTS);
            $serviceQuery = $this->Browser->serviceQuery($this->MY_RIGHTS);
        }

        $hosts = $this->Host->find('all', $hostQuery);
        $services = $this->Service->find('all', $serviceQuery);

        $state_array_host = $this->Browser->countHoststate($hosts);
        $state_array_service = $this->Browser->countServicestate($services);

        $this->set(compact([
            'recursive',
            'parents',
            'top_node',
            'state_array_host',
            'state_array_service',
            'tenants',
            'hosts',
        ]));


        /***** NEW ***/
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        if (!$this->isApiRequest()) {
            $masterInstanceName = $this->Systemsetting->getMasterInstanceName();
            $SatelliteNames = [];
            $ModuleManager = new ModuleManager('DistributeModule');
            if ($ModuleManager->moduleExists()) {
                $SatelliteModel = $ModuleManager->loadModel('Satellite');
                $SatelliteNames = $SatelliteModel->find('list');
                $SatelliteNames[0] = $masterInstanceName;
            }

            $this->set('QueryHandler', new QueryHandler($this->Systemsetting->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }

        if ((int)$containerId === ROOT_CONTAINER) {
            //First request or ROOT_CONTAINER
            $tenants = $this->getTenants();
            natcasesort($tenants);
            $this->set('containers', $this->Container->makeItJavaScriptAble($tenants));
            $this->set('breadcrumbs', $this->Container->makeItJavaScriptAble([ROOT_CONTAINER => __('root')]));
        } else {
            //Child container (or so)

            if ($this->hasRootPrivileges === true) {
                $browser = Hash::extract($this->Container->children($containerId, true), '{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_NODE . ')$/]');
            } else {
                $containerNest = Hash::nest($this->Container->children($containerId));
                $browser = $this->Browser->getFirstContainers($containerNest, $this->MY_RIGHTS, [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]);
            }

            $browser = Hash::sort($browser, '{n}.name', 'asc', ['type' => 'regular', 'ignoreCase' => true]);

            if ($this->hasRootPrivileges === false) {
                foreach ($browser as $key => $containerRecord) {
                    if (!in_array($containerRecord['id'], $this->MY_RIGHTS)) {
                        unset($browser[$key]);
                    }
                }
            }

            $containers = [];
            foreach ($browser as $node) {
                $containers[$node['id']] = $node['name'];
            }

            $currentContainer = $this->Container->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Container.id' => $containerId
                ]
            ]);
            $breadcrumbs = [];
            $parents = $this->Container->getPath($currentContainer['Container']['parent_id']);
            foreach ($parents as $parentContainer) {
                $breadcrumbs[$parentContainer['Container']['id']] = $parentContainer['Container']['name'];
            }
            $breadcrumbs[$currentContainer['Container']['id']] = $currentContainer['Container']['name'];
            $this->set('containers', $this->Container->makeItJavaScriptAble($containers));
            $this->set('breadcrumbs', $this->Container->makeItJavaScriptAble($breadcrumbs));

        }


        $this->set('recursiveBrowser', $User->isRecursiveBrowserEnabled());
        $this->set('_serialize', ['containers', 'recursiveBrowser', 'breadcrumbs']);
    }


    function tenantBrowser($id) {
        if (!$this->Container->exists($id)) {
            throw new NotFoundException(__('Invalid container'));
        }
        $MY_RIGHTS_WITH_TENANT = array_merge($this->MY_RIGHTS, array_keys($this->getTenants()));

        if (!$this->hasRootPrivileges) {
            if (!in_array($id, $MY_RIGHTS_WITH_TENANT)) {
                $this->render403();

                return;
            }
        }

        if ($this->hasRootPrivileges === true) {
            $browser = Hash::extract($this->Container->children($id, true), '{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_NODE . ')$/]');
        } else {
            $containerNest = Hash::nest($this->Container->children($id));
            $browser = $this->Browser->getFirstContainers($containerNest, $this->MY_RIGHTS, [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]);
        }

        $browser = Hash::sort($browser, '{n}.name', 'asc', ['type' => 'regular', 'ignoreCase' => true]);

        if ($this->hasRootPrivileges === false) {
            foreach ($browser as $key => $containerRecord) {
                if (!in_array($containerRecord['id'], $this->MY_RIGHTS)) {
                    unset($browser[$key]);
                }
            }
        }

        $recursive = $this->Auth->user('recursive_browser');

        $hosts = $services = [];
        if (in_array($id, $this->MY_RIGHTS)) {
            $lookupIds = $id;
            if ($recursive) {
                if ($this->hasRootPrivileges === true) {
                    //root user
                    $lookupIds = Hash::extract($this->Container->children($id), '{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_NODE . ')$/].id');
                    $lookupIds = array_merge($lookupIds, [$id]);
                } else {
                    //non root user
                    $lookupIds = Hash::extract($this->Container->children($id), '{n}.Container[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_NODE . ')$/].id');
                    $lookupIds = array_merge($lookupIds, [$id]);
                    if (is_array($lookupIds) && !empty($lookupIds)) {
                        foreach ($lookupIds as $key => $currentId) {
                            if (!in_array($currentId, $this->MY_RIGHTS)) {
                                unset($lookupIds[$key]);
                            }
                        }
                    }
                }
            }

            $query = $this->Browser->hostsQuery($lookupIds);
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $hostsForCounter = $this->Host->find('all', $query);
            $hosts = $this->Paginator->paginate('Host');
            $query = $this->Browser->serviceQuery($lookupIds);
            $services = $this->Service->find('all', $query);
        }
        $state_array_host = $this->Browser->countHoststate($hostsForCounter);
        $state_array_service = $this->Browser->countServicestate($services);

        $currentContainer = $this->Container->findById($id);
        $parents = $this->Container->getPath($currentContainer['Container']['parent_id']);
        $this->set(compact([
            'currentContainer',
            'browser',
            'parents',
            'state_array_host',
            'state_array_service',
            'hosts',
            'MY_RIGHTS_WITH_TENANT',
        ]));
    }

    /**
     * @return mixed
     */
    private function getTenants() {
        return $this->Tenant->tenantsByContainerId(
            array_merge(
                $this->MY_RIGHTS, array_keys(
                    $this->User->getTenantIds(
                        $this->Auth->user('id')
                    )
                )
            ),
            'list', 'container_id');
    }
}
