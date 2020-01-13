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

use App\Model\Table\ContainersTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\TenantsTable;
use Cake\Core\Plugin;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;

/**
 * Class BrowsersController
 * @package App\Controller
 */
class BrowsersController extends AppController {

    /**
     * @param int|null $containerId
     */
    function index($containerId = null) {
        $User = new User($this->getUser());

        if (!$this->isApiRequest()) {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $masterInstanceName = $SystemsettingsTable->getMasterInstanceName();
            $SatelliteNames = [];


            if (Plugin::isLoaded('DistributeModule')) {
                /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
                $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');

                $satellites = $SatellitesTable->getSatellitesAsList($this->MY_RIGHTS);
                $satellites[0] = $masterInstanceName;
            }

            $this->set('username', $User->getFullName());
            $this->set('satellites', $SatelliteNames);
            //Only ship HTML template
            return;
        }

        if ($containerId === null) {
            throw new BadRequestException("containerId is missing");
        }
        $containerId = (int)$containerId;

        /** @var TenantsTable $TenantsTable */
        $TenantsTable = TableRegistry::getTableLocator()->get('Tenants');

        $tenants = $TenantsTable->getTenantsForBrowsersIndex($this->MY_RIGHTS, $User->getId());

        $tenantsFiltered = [];
        foreach ($tenants as $tenantId => $tenantName) {
            if (in_array($tenantId, $this->MY_RIGHTS, true)) {
                $tenantsFiltered[$tenantId] = $tenantName;
            }
        }
        $tenants = $tenantsFiltered;
        natcasesort($tenants);

        /** @var ContainersTable $ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($containerId === ROOT_CONTAINER && !empty($tenants)) {
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
        $this->viewBuilder()->setOption('serialize', ['containers', 'recursiveBrowser', 'breadcrumbs']);
    }
}
