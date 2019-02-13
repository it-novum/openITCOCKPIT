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
use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
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
        $this->layout = 'blank';

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        if (!$this->isApiRequest()) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $masterInstanceName = $Systemsettings->getMasterInstanceName();
            $SatelliteNames = [];
            $ModuleManager = new ModuleManager('DistributeModule');
            if ($ModuleManager->moduleExists()) {
                $SatelliteModel = $ModuleManager->loadModel('Satellite');
                $SatelliteNames = $SatelliteModel->find('list');
                $SatelliteNames[0] = $masterInstanceName;
            }

            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $this->set('QueryHandler', new QueryHandler($Systemsettings->getQueryHandlerPath()));
            $this->set('username', $User->getFullName());
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }

        $tenants = $this->getTenants();
        $tenantsFiltered = [];
        foreach ($tenants as $tenantId => $tenantName) {
            if (in_array($tenantId, $this->MY_RIGHTS, true)) {
                $tenantsFiltered[$tenantId] = $tenantName;
            }
        }
        $tenants = $tenantsFiltered;
        natcasesort($tenants);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ((int)$containerId === ROOT_CONTAINER && !empty($tenants)) {
            //First request if tenants are not empty or ROOT_CONTAINER

            $this->set('containers', Api::makeItJavaScriptAble($tenants));
            $this->set('breadcrumbs', Api::makeItJavaScriptAble([ROOT_CONTAINER => __('root')]));
        } else {
            //Child container (or so)

            if ($this->hasRootPrivileges === true) {
                $children = $ContainersTable->getChildren($containerId);
                $browser = Hash::extract($children, '{n}[containertype_id=/^(' . CT_GLOBAL . '|' . CT_TENANT . '|' . CT_LOCATION . '|' . CT_NODE . ')$/]');
            } else {
                $containerNest = $ContainersTable->getChildren($containerId, true);
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

            $currentContainer = $ContainersTable->get($containerId)->toArray();

            $breadcrumbs = [];
            $parents = $ContainersTable->getPathByIdAndCacheResult($currentContainer['parent_id'], 'BrowsersIndex');

            foreach ($parents as $parentContainer) {
                if (in_array((int)$parentContainer['id'], $this->MY_RIGHTS, true)) {
                    $breadcrumbs[$parentContainer['id']] = $parentContainer['name'];
                }
            }
            $breadcrumbs[$currentContainer['id']] = $currentContainer['name'];
            $this->set('containers', Api::makeItJavaScriptAble($containers));
            $this->set('breadcrumbs', Api::makeItJavaScriptAble($breadcrumbs));

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
