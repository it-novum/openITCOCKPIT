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
 * @property Systemsetting Systemsetting
 * @property Browser Browser
 * @property Tenant Tenant
 * @property AppAuthComponent Auth
 */
class BrowsersController extends AppController {

    public $layout = 'angularjs';

    public $uses = [
        'Systemsetting',
        'Container',
        'Browser',
        'Tenant'
    ];

    public $components = [
        'paginator',
    ];

    function index($containerId = null) {
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
