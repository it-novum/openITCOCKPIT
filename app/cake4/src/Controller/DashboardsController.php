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
use App\Model\Table\DashboardTabsTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\UsersTable;
use App\Model\Table\WidgetsTable;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Dashboards\DowntimeHostListJson;
use itnovum\openITCOCKPIT\Core\Dashboards\DowntimeServiceListJson;
use itnovum\openITCOCKPIT\Core\Dashboards\HostStatusListJson;
use itnovum\openITCOCKPIT\Core\Dashboards\HostStatusOverviewJson;
use itnovum\openITCOCKPIT\Core\Dashboards\NoticeJson;
use itnovum\openITCOCKPIT\Core\Dashboards\ServiceStatusListJson;
use itnovum\openITCOCKPIT\Core\Dashboards\ServiceStatusOverviewJson;
use itnovum\openITCOCKPIT\Core\Dashboards\TachoJson;
use itnovum\openITCOCKPIT\Core\Dashboards\TrafficlightJson;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use Statusengine\PerfdataParser;

/**
 * Class DashboardsController
 * @property DashboardTab $DashboardTab
 * @property Widget $Widget
 * @property Parenthost $Parenthost
 * @property Hoststatus $Hoststatus
 * @property User $User
 * @property Servicestatus $Servicestatus
 * @property Service $Service
 */
class DashboardsController extends AppController {

    use LocatorAwareTrait;


    public function index() {
        //CakePHP 4 Model usage Example
        //$TableLocator = $this->getTableLocator();
        //$Proxy = $TableLocator->get('Proxies');
        //debug($Proxy->find()->first());die();

        if (!$this->isAngularJsRequest()) {
            $askForHelp = false;
            $askAgainForHelp = $this->request->getCookie('askAgainForHelp');
            if ($askAgainForHelp === null) {

                /** @var $SystemsettingsTable SystemsettingsTable */
                $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

                $record = $SystemsettingsTable->getSystemsettingByKeyAsCake2('SYSTEM.ANONYMOUS_STATISTICS');
                if (!empty($record)) {
                    if ($record['Systemsetting']['value'] === '2') {
                        $askForHelp = true;
                    }
                }
            }
            $this->set('askForHelp', $askForHelp);

            //Only ship template
            return;
        }

        $User = new User($this->getUser());

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $UsersTable->get($User->getId());
        $tabRotationInterval = (int)$user->dashboard_tab_rotation;

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');


        //Check if a tab exists for the given user
        if ($DashboardTabsTable->hasUserATab($User->getId()) === false) {
            $entitiy = $DashboardTabsTable->createNewTab($User->getId());
            if ($entitiy) {
                //Create default widgets
                $entitiy = $DashboardTabsTable->patchEntity($entitiy, [
                    'widgets' => $WidgetsTable->getDefaultWidgets($this->PERMISSIONS)
                ]);

                $DashboardTabsTable->save($entitiy);
            }
        }
        $tabs = $DashboardTabsTable->getAllTabsByUserId($User->getId());

        $widgets = $WidgetsTable->getAvailableWidgets($this->PERMISSIONS);

        $this->set('tabs', $tabs);
        $this->set('widgets', $widgets);
        $this->set('tabRotationInterval', $tabRotationInterval);
        $this->viewBuilder()->setOption('serialize', ['tabs', 'widgets', 'tabRotationInterval']);
    }

    public function getWidgetsForTab($tabId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        if (!$DashboardTabsTable->existsById($tabId)) {
            throw new NotFoundException(sprintf('Tab width id %s not found', $tabId));
        }

        $User = new User($this->getUser());
        $widgets = $DashboardTabsTable->getWidgetsForTabByUserIdAndTabId($User->getId(), $tabId);

        $this->set('widgets', $widgets);
        $this->viewBuilder()->setOption('serialize', ['widgets']);
    }

    public function dynamicDirective() {
        $directiveName = $this->request->getQuery('directive');

        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        $widgets = $WidgetsTable->getAvailableWidgets($this->PERMISSIONS);
        $isValidDirective = false;
        foreach ($widgets as $widget) {
            if ($widget['directive'] === $directiveName) {
                $isValidDirective = true;
                break;
            }
        }

        if (!$isValidDirective) {
            throw new ForbiddenException();
        }

        if (strlen($directiveName) < 2) {
            throw new \RuntimeException('Wrong AngularJS directive name?');
        }
        $this->set('directiveName', $directiveName);
    }

    public function saveGrid() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $widgetsData = $this->request->getData(null, []);
        //Update DashboardTab last modified
        if (isset($widgetsData[0]['Widget']['dashboard_tab_id'])) {
            $User = new User($this->getUser());
            /** @var DashboardTabsTable $DashboardTabsTable */
            $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
            /** @var WidgetsTable $WidgetsTable */
            $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

            //Save all Widgets
            $widgets = [];
            foreach ($widgetsData as $widgetData) {
                $widget = $WidgetsTable->get($widgetData['Widget']['id']);
                $widget = $WidgetsTable->patchEntity($widget, $widgetData['Widget']);
                $widgets[] = $widget;
            }
            if ($WidgetsTable->saveMany($widgets)) {
                $tab = $DashboardTabsTable->find()
                    ->where([
                        'DashboardTabs.id'      => $widgetsData[0]['Widget']['dashboard_tab_id'],
                        'DashboardTabs.user_id' => $User->getId()
                    ])
                    ->first();

                if ($tab) {
                    $tab->set('modified', date('Y-m-d H:i:s'));
                    $DashboardTabsTable->save($tab);
                }

                $this->set('message', __('Successfully saved'));
                $this->viewBuilder()->setOption('serialize', ['message']);
                return;
            }
            $this->serializeCake4ErrorMessage($widgets[0]);
        }
    }

    public function addWidgetToTab() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $widget = $this->request->getData('Widget', []);
        if (!isset($widget['typeId']) || !isset($widget['dashboard_tab_id'])) {
            throw new \RuntimeException('Missing parameter typeId || dashboard_tab_id');
        }

        $widget['dashboard_tab_id'] = (int)$widget['dashboard_tab_id'];

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        if (!$DashboardTabsTable->existsById($widget['dashboard_tab_id'])) {
            throw new NotFoundException('DashboardTab does not exists!');
        }

        if (!$WidgetsTable->isWidgetAvailable($widget['typeId'], $this->PERMISSIONS)) {
            throw new NotFoundException('Widget not found!');
        }

        $widgetDefaults = $WidgetsTable->getWidgetByTypeId($widget['typeId'], $this->PERMISSIONS);
        $data = [
            'dashboard_tab_id' => $widget['dashboard_tab_id'],
            'type_id'          => $widget['typeId'],
            'row'              => 0,
            'col'              => 0,
            'width'            => $widgetDefaults['width'],
            'height'           => $widgetDefaults['height'],
            'title'            => $widgetDefaults['title'],
            'icon'             => $widgetDefaults['icon'],
            'color'            => 'jarviswidget-color-blueDark',
            'directive'        => $widgetDefaults['directive']
        ];

        $entity = $WidgetsTable->newEntity($data);
        $WidgetsTable->save($entity);
        if ($entity->hasErrors()) {
            $this->set('error', $entity->getErrors());
            $this->viewBuilder()->setOption('serialize', ['error']);
            return $this->response->withStatus(400);
        }

        $this->set('message', __('Successfully saved'));
        $this->set('widget', $WidgetsTable->getWidgetByIdAsCake2($entity->get('id')));
        $this->viewBuilder()->setOption('serialize', ['message', 'widget']);
    }

    public function removeWidgetFromTab() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $widget = $this->request->getData('Widget', []);
        if (!isset($widget['id']) || !isset($widget['dashboard_tab_id'])) {
            throw new \RuntimeException('Missing parameter id || dashboard_tab_id');
        }

        $widget['id'] = (int)$widget['id'];

        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        if (!$WidgetsTable->existsById($widget['id'])) {
            throw new NotFoundException('Widget does not exists!');
        }
        if ($WidgetsTable->delete($WidgetsTable->get($widget['id']))) {
            $this->set('message', __('Successfully deleted'));
            $this->viewBuilder()->setOption('serialize', ['message', 'widget']);
            return;
        }

        $this->set('message', __('Error while deleting Widget'));
        $this->viewBuilder()->setOption('serialize', ['message']);
        return $this->response->withStatus(400);
    }

    public function saveTabOrder() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $newOrder = $this->request->getData('order', []);
        if (empty($newOrder) || !is_array($newOrder)) {
            return;
        }

        $User = new User($this->getUser());
        $success = true;

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        foreach ($newOrder as $key => $tabId) {
            $tab = $DashboardTabsTable->find()
                ->where([
                    'DashboardTabs.id'      => $tabId,
                    'DashboardTabs.user_id' => $User->getId()
                ])
                ->first();

            if ($tab) {
                $tab = $DashboardTabsTable->patchEntity($tab, [
                    'position' => (int)$key
                ]);

                $DashboardTabsTable->save($tab);
                if ($tab->hasErrors()) {
                    $success = false;
                }
            }
        }

        $this->set('success', $success);
        $this->viewBuilder()->setOption('serialize', ['success']);
        if ($success === false) {
            return $this->response->withStatus(400);
        }
    }

    public function addNewTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $name = $this->request->getData('DashboardTab.name');
        $User = new User($this->getUser());

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        $entity = $DashboardTabsTable->createNewTab($User->getId(), [
            'name' => $name
        ]);

        if ($entity->hasErrors()) {
            return $this->serializeCake4ErrorMessage($entity);
        }

        $this->set('DashboardTab', $DashboardTabsTable->getTabByIdAsCake2($entity->get('id')));
        $this->viewBuilder()->setOption('serialize', ['DashboardTab']);
    }

    public function saveTabRotateInterval() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $tabRotationInterval = (int)$this->request->getData('User.dashboard_tab_rotation', 0);

        $User = new User($this->getUser());

        /** @var UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        $entity = $UsersTable->get($User->getId());
        $entity = $UsersTable->patchEntity($entity, [
            'dashboard_tab_rotation' => $tabRotationInterval
        ]);
        $UsersTable->save($entity);

        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function renameDashboardTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $name = $this->request->getData('DashboardTab.name');
        $id = (int)$this->request->getData('DashboardTab.id');

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        $tab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $id,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        if ($tab === null) {
            throw new NotFoundException();
        }

        $tab = $DashboardTabsTable->patchEntity($tab, [
            'name' => $name
        ]);

        $DashboardTabsTable->save($tab);
        if ($tab->hasErrors()) {
            return $this->serializeCake4ErrorMessage($tab);
        }

        $this->set('DashboardTab', [
            'DashboardTab' => $DashboardTabsTable->getTabByIdAsCake2($tab->get('id'))
        ]);
        $this->viewBuilder()->setOption('serialize', ['DashboardTab']);
    }

    public function deleteDashboardTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->getData('DashboardTab.id');

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        $tab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $id,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        if ($tab === null) {
            throw new NotFoundException();
        }

        if ($DashboardTabsTable->delete($tab)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }
        $this->set('success', false);
        $this->viewBuilder()->setOption('serialize', ['success']);
        return $this->response->withStatus(400);
    }

    public function startSharing() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->getData('DashboardTab.id', 0);
        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        $tab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $id,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        if ($tab === null) {
            throw new NotFoundException();
        }

        $tab = $DashboardTabsTable->patchEntity($tab, [
            'shared' => 1
        ]);

        $DashboardTabsTable->save($tab);

        if ($tab->hasErrors()) {
            return $this->serializeCake4ErrorMessage($tab);
        }

        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function stopSharing() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->getData('DashboardTab.id', 0);
        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        $tab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $id,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        if ($tab === null) {
            throw new NotFoundException();
        }

        $tab = $DashboardTabsTable->patchEntity($tab, [
            'shared' => 0
        ]);

        $DashboardTabsTable->save($tab);

        if ($tab->hasErrors()) {
            return $this->serializeCake4ErrorMessage($tab);
        }

        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function getSharedTabs() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
        $tabs = $DashboardTabsTable->getSharedTabs();

        $this->set('tabs', $tabs);
        $this->viewBuilder()->setOption('serialize', ['tabs']);
    }

    public function createFromSharedTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->getData('DashboardTab.id', 0);

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');

        $newTab = $DashboardTabsTable->copySharedTab($id, $User->getId());
        if ($newTab->hasErrors()) {
            return $this->serializeCake4ErrorMessage($newTab);
        }

        $this->set('DashboardTab', [
            'DashboardTab' => [
                'id' => $newTab->get('id')
            ]
        ]);
        $this->viewBuilder()->setOption('serialize', ['DashboardTab']);
    }

    public function checkForUpdates() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $tabId = (int)$this->request->getQuery('tabId', 0);
        $User = new User($this->getUser());

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
        if (!$DashboardTabsTable->existsById($tabId)) {
            throw new NotFoundException('DashboardTab not found');
        }

        $tab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $tabId,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        $sourceTab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id' => $tab->get('source_tab_id')
            ])
            ->first();

        $updateAvailable = false;
        if ($sourceTab !== null && $tab !== null) {
            /** @var FrozenTime $modified */
            $modified = $sourceTab->get('modified');
            $modified = $modified->getTimestamp();

            if ($modified > $tab->get('last_update')) {
                $updateAvailable = true;
            }
        }

        $this->set('updateAvailable', $updateAvailable);
        $this->viewBuilder()->setOption('serialize', ['updateAvailable']);
    }

    public function neverPerformUpdates() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->getData('DashboardTab.id', 0);

        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
        if (!$DashboardTabsTable->existsById($id)) {
            throw new NotFoundException('DashboardTab not found');
        }

        $tab = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $id,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        $tab = $DashboardTabsTable->patchEntity($tab, [
            'check_for_updates' => 0
        ]);
        $DashboardTabsTable->save($tab);

        $dashboardTab['DashboardTab']['check_for_updates'] = 0;

        if ($tab->hasErrors()) {
            return $this->serializeCake4ErrorMessage($tab);
        }

        $this->set('success', true);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    public function updateSharedTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->getData('DashboardTab.id', 0);


        /** @var DashboardTabsTable $DashboardTabsTable */
        $DashboardTabsTable = TableRegistry::getTableLocator()->get('DashboardTabs');
        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        $tabToUpdate = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id'      => $id,
                'DashboardTabs.user_id' => $User->getId()
            ])
            ->first();

        if ($tabToUpdate === null) {
            throw new NotFoundException();
        }

        $sourceTabWithWidgets = $DashboardTabsTable->find()
            ->where([
                'DashboardTabs.id' => $tabToUpdate->get('source_tab_id')
            ])
            ->contain([
                'Widgets'
            ])
            ->first();

        if ($sourceTabWithWidgets === null) {
            throw new NotFoundException();
        }

        $widgets = [];
        foreach ($sourceTabWithWidgets->get('widgets') as $widget) {
            $widgets[] = [
                'type_id'    => $widget->get('type_id'),
                'host_id'    => $widget->get('host_id'),
                'service_id' => $widget->get('service_id'),
                'row'        => $widget->get('row'),
                'col'        => $widget->get('col'),
                'width'      => $widget->get('width'),
                'height'     => $widget->get('height'),
                'title'      => $widget->get('title'),
                'color'      => $widget->get('color'),
                'directive'  => $widget->get('directive'),
                'icon'       => $widget->get('icon'),
                'json_data'  => $widget->get('json_data')
            ];
        }

        $tabToUpdate = $DashboardTabsTable->patchEntity($tabToUpdate, [
            'last_update' => time(),
            'locked'      => (bool)$sourceTabWithWidgets->get('locked'),
            'widgets'     => $widgets
        ]);


        //Delete old widgets
        $WidgetsTable->deleteAll([
            'Widgets.dashboard_tab_id' => $id
        ]);

        $DashboardTabsTable->save($tabToUpdate);

        if ($tabToUpdate->hasErrors()) {
            return $this->serializeCake4ErrorMessage($tabToUpdate);
        }

        $this->set('DashboardTab', [
            'DashboardTab' => $tabToUpdate
        ]);
        $this->viewBuilder()->setOption('serialize', ['DashboardTab']);
    }

    /**
     * @deprecated
     */
    public function renameWidget() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $widgetId = (int)$this->request->data('Widget.id');
        $name = $this->request->data('Widget.name');

        $widget = $this->Widget->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Widget.id' => $widgetId
            ]
        ]);

        if (empty($widget)) {
            throw new NotFoundException();
        }

        $widget['Widget']['name'] = $name;
        if ($this->Widget->save($widget)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }

        $this->serializeErrorMessageFromModel('Widget');
    }

    /**
     * @deprecated
     */
    public function lockOrUnlockTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->data('DashboardTab.id');
        $locked = $this->request->data('DashboardTab.locked') === 'true';

        $dashboardTab = $this->DashboardTab->find('first', [
            'conditions' => [
                'DashboardTab.id'      => $id,
                'DashboardTab.user_id' => $User->getId()
            ]
        ]);

        if (empty($dashboardTab)) {
            throw new NotFoundException();
        }

        $dashboardTab['DashboardTab']['locked'] = $locked;

        if ($this->DashboardTab->save($dashboardTab)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    /**
     * @deprecated
     */
    public function restoreDefault() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new User($this->getUser());

        $id = (int)$this->request->data('DashboardTab.id');

        $dashboardTab = $this->DashboardTab->find('first', [
            'conditions' => [
                'DashboardTab.id'      => $id,
                'DashboardTab.user_id' => $User->getId()
            ]
        ]);

        if (empty($dashboardTab)) {
            throw new NotFoundException();
        }

        //Delete old widgets
        $this->Widget->deleteAll([
            'Widget.dashboard_tab_id' => $id
        ]);

        $dashboardTab['Widget'] = $this->Widget->getDefaultWidgets($this->PERMISSIONS);

        if ($this->DashboardTab->saveAll($dashboardTab)) {
            $this->set('success', true);
            $this->viewBuilder()->setOption('serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    /***** Basic Widgets *****/
    /**
     * @deprecated
     */
    public function welcomeWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
    }

    /**
     * @deprecated
     */
    public function parentOutagesWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $containerIds = [];
        if ($this->hasRootPrivileges === false) {
            $containerIds = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
        }

        $query = [
            'recursive' => -1,
            'fields'    => [
                'DISTINCT Host.uuid',
                'Host.id',
                'Host.name'
            ],

            'joins' => [
                [
                    'table'      => 'hosts',
                    'type'       => 'INNER',
                    'alias'      => 'Host',
                    'conditions' => 'Parenthost.parenthost_id = Host.id'

                ]
            ],
            'order' => [
                'Host.name' => 'asc'
            ],
            'group' => 'Parenthost.parenthost_id'
        ];

        if (!empty($containerIds)) {
            $query['joins'][] = [
                'table'      => 'hosts_to_containers',
                'alias'      => 'HostsToContainers',
                'type'       => 'LEFT',
                'conditions' => [
                    'HostsToContainers.host_id = Host.id',
                ],
            ];
            $query['conditions']['HostsToContainers.container_id'] = $containerIds;
        }

        $parentHosts = $this->Parenthost->find('all', $query);
        $hostUuids = Hash::extract($parentHosts, '{n}.Host.uuid');
        $HoststatusFields = new \itnovum\openITCOCKPIT\Core\HoststatusFields($this->DbBackend);
        $HoststatusFields->currentState();
        $HoststatusConditions = new \itnovum\openITCOCKPIT\Core\HoststatusConditions($this->DbBackend);
        $HoststatusConditions->hostsDownAndUnreachable();
        $hoststatus = $this->Hoststatus->byUuid($hostUuids, $HoststatusFields, $HoststatusConditions);
        $query['conditions']['Host.uuid'] = array_keys($hoststatus);

        if (isset($this->request->query['filter']['Host.name']) && strlen($this->request->query['filter']['Host.name']) > 0) {
            $query['conditions']['Host.name LIKE'] = sprintf('%%%s%%', $this->request->query['filter']['Host.name']);
        }

        $parent_outages = $this->Parenthost->find('all', $query);

        $this->set(compact(['parent_outages']));
        $this->viewBuilder()->setOption('serialize', ['parent_outages']);
    }

    public function hostsPiechartWidget() {
        return;
    }

    public function hostsPiechart180Widget() {
        return;
    }


    public function servicesPiechartWidget() {
        return;
    }

    public function servicesPiechart180Widget() {
        return;
    }

    /**
     * @deprecated
     */
    public function hostsStatusListWidget() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $widgetId = (int)$this->request->query('widgetId');
        $HostStatusListJson = new HostStatusListJson();
        if (!$this->Widget->exists($widgetId)) {
            throw new NotFoundException('Widget not found');
        }

        if ($this->request->is('get')) {
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $HostStatusListJson->standardizedData($data);
            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $HostStatusListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function hostsDowntimeWidget() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }
        $widgetId = (int)$this->request->query('widgetId');
        $DowntimeHostListJson = new DowntimeHostListJson();

        if (!$this->Widget->exists($widgetId)) {
            throw new NotFoundException('Widget not found');
        }

        if ($this->request->is('get')) {
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $DowntimeHostListJson->standardizedData($data);
            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $DowntimeHostListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function servicesDowntimeWidget() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }
        $widgetId = (int)$this->request->query('widgetId');
        $DowntimeServiceListJson = new DowntimeServiceListJson();

        if (!$this->Widget->exists($widgetId)) {
            throw new NotFoundException('Widget not found');
        }

        if ($this->request->is('get')) {
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $DowntimeServiceListJson->standardizedData($data);
            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $DowntimeServiceListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function servicesStatusListWidget() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }
        $widgetId = (int)$this->request->query('widgetId');
        $ServiceStatusListJson = new ServiceStatusListJson();
        if (!$this->Widget->exists($widgetId)) {
            throw new NotFoundException('Widget not found');
        }

        if ($this->request->is('get')) {
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $ServiceStatusListJson->standardizedData($data);
            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $ServiceStatusListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function noticeWidget() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }
        $widgetId = (int)$this->request->query('widgetId');
        $NoticeJson = new NoticeJson();

        if (!$this->Widget->exists($widgetId)) {
            throw new NotFoundException('Widget not found');
        }

        if ($this->request->is('get')) {
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            $htmlContent = '';
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
                if (!empty($data['note'])) {
                    $parseDown = new ParsedownExtra();
                    $htmlContent = $parseDown->text($data['note']);
                }
            }
            $config = $NoticeJson->standardizedData($data);
            $this->set('config', $config);
            $this->set('htmlContent', $htmlContent);

            $this->viewBuilder()->setOption('serialize', ['config', 'htmlContent']);
            return;
        }


        if ($this->request->is('post')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
            ]);
            if ($widget) {
                $widget['Widget']['json_data'] = json_encode([
                    'note' => $this->request->data('note')
                ]);
                if (!$this->Widget->save($widget)) {
                    $this->response->statusCode(400);
                    $this->serializeErrorMessageFromModel('Widget');
                    return;
                }
                $this->viewBuilder()->setOption('serialize', ['serviceId']);
                return;
            }
            $this->response->statusCode(400);
            return;
        }
        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function trafficLightWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $TrafficlightJson = new TrafficlightJson();

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
                'fields'     => [
                    'Widget.service_id',
                    'Widget.json_data'
                ]
            ]);
            $serviceId = (int)$widget['Widget']['service_id'];
            if ($serviceId === 0) {
                $serviceId = null;
            }

            $service = $this->getServicestatusByServiceId($serviceId);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $TrafficlightJson->standardizedData($data);
            $this->set('config', $config);
            $this->set('service', $service);
            $this->set('ACL', $this->getAcls());
            $this->viewBuilder()->setOption('serialize', ['service', 'config', 'ACL']);
            return;
        }


        if ($this->request->is('post')) {
            $config = $TrafficlightJson->standardizedData($this->request->data);
            $widgetId = (int)$this->request->data('Widget.id');
            $serviceId = (int)$this->request->data('Widget.service_id');

            if (!$this->Widget->exists($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
            ]);

            $widget['Widget']['service_id'] = $serviceId;
            $widget['Widget']['json_data'] = json_encode($config);
            if ($this->Widget->save($widget)) {
                $service = $this->getServicestatusByServiceId($serviceId);
                $this->set('service', $service);
                $this->set('config', $config);
                $this->set('ACL', $this->getAcls());
                $this->viewBuilder()->setOption('serialize', ['service', 'config', 'ACL']);
                return;
            }

            $this->serializeErrorMessageFromModel('Widget');
            return;
        }
        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function tachoWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $TachoJson = new TachoJson();

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
                'fields'     => [
                    'Widget.service_id',
                    'Widget.json_data'
                ]
            ]);
            $serviceId = (int)$widget['Widget']['service_id'];
            if ($serviceId === 0) {
                $serviceId = null;
            }

            $service = $this->getServicestatusByServiceId($serviceId);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $TachoJson->standardizedData($data);
            $this->set('config', $config);
            $this->set('service', $service);
            $this->set('ACL', $this->getAcls());
            $this->viewBuilder()->setOption('serialize', ['service', 'config', 'ACL']);
            return;
        }


        if ($this->request->is('post')) {
            $config = $TachoJson->standardizedData($this->request->data);
            $widgetId = (int)$this->request->data('Widget.id');
            $serviceId = (int)$this->request->data('Widget.service_id');

            if (!$this->Widget->exists($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ],
            ]);

            $widget['Widget']['service_id'] = $serviceId;
            $widget['Widget']['json_data'] = json_encode($config);
            if ($this->Widget->save($widget)) {
                $service = $this->getServicestatusByServiceId($serviceId);
                $this->set('service', $service);
                $this->set('config', $config);
                $this->set('ACL', $this->getAcls());
                $this->viewBuilder()->setOption('serialize', ['service', 'config', 'ACL']);
                return;
            }

            $this->serializeErrorMessageFromModel('Widget');
            return;
        }
        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    private function getServicestatusByServiceId($id) {
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.name'
                    ],
                ],
            ],
            'joins'      => [
                [
                    'table'      => 'hosts',
                    'alias'      => 'Host',
                    'type'       => 'INNER',
                    'conditions' => [
                        'Host.id = Service.host_id'
                    ]
                ],
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'INNER',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ],
            ],
            'fields'     => [
                'Service.id',
                'Service.disabled',
                'Service.name',
                'Service.uuid',
                'Service.service_type',
                'Host.id',
                'Host.name'
            ],
            'conditions' => [
                'Service.id' => $id,

            ],
        ];

        if (!$this->hasRootPrivileges) {
            $query['conditions']['HostsToContainers.container_id'] = $this->MY_RIGHTS;
        }

        $service = $this->Service->find('first', $query);
        if (!empty($service)) {
            $ServicestatusFields = new ServicestatusFields($this->DbBackend);
            $ServicestatusFields->currentState()->isFlapping()->perfdata();
            $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);

            if (!empty($servicestatus)) {
                $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);
            } else {
                $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus(
                    ['Servicestatus' => []]
                );
            }
            $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service);
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service);
            $PerfdataParser = new PerfdataParser($Servicestatus->getPerfdata());


            $serviceForJs = [
                'Host'          => $Host->toArray(),
                'Service'       => $Service->toArray(),
                'Servicestatus' => $Servicestatus->toArray(),
                'Perfdata'      => $PerfdataParser->parse()
            ];

            $serviceForJs['Service']['isGenericService'] = $service['Service']['service_type'] == GENERIC_SERVICE;
            $serviceForJs['Service']['isEVCService'] = $service['Service']['service_type'] == EVK_SERVICE;
            $serviceForJs['Service']['isSLAService'] = $service['Service']['service_type'] == SLA_SERVICE;
            $serviceForJs['Service']['isMkService'] = $service['Service']['service_type'] == MK_SERVICE;

            $serviceForJs['Service']['id'] = (int)$serviceForJs['Service']['id'];
            $serviceForJs['Host']['id'] = (int)$serviceForJs['Host']['id'];

            return $serviceForJs;
        }
        return [
            'Service'       => [],
            'Servicestatus' => []
        ];
    }

    /**
     * @return array
     * @deprecated
     */
    private function getAcls() {
        $acl = [
            'hosts'    => [
                'browser' => isset($this->PERMISSIONS['hosts']['browser']),
                'index'   => isset($this->PERMISSIONS['hosts']['index'])
            ],
            'services' => [
                'browser' => isset($this->PERMISSIONS['services']['browser']),
                'index'   => isset($this->PERMISSIONS['services']['index'])

            ],
            'evc'      => [
                'view' => isset($this->PERMISSIONS['eventcorrelationmodule']['eventcorrelations']['view']),
            ],
        ];
        return $acl;
    }

    /**
     * @deprecated
     */
    public function hostStatusOverviewWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        $HostStatusOverviewJson = new HostStatusOverviewJson();

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new NotFoundException('Widget not found');
            }

            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $HostStatusOverviewJson->standardizedData($data);

            if ($this->DbBackend->isNdoUtils()) {
                $query = $this->Host->getHoststatusCountBySelectedStatus($this->MY_RIGHTS, $config);
                $modelName = 'Host';
            }

            if ($this->DbBackend->isCrateDb()) {
                $query = $this->Hoststatus->getHoststatusCountBySelectedStatus($this->MY_RIGHTS, $config);
                $modelName = 'Hoststatus';
            }

            if ($this->DbBackend->isStatusengine3()) {
                $query = $this->Host->getHoststatusBySelectedStatusStatusengine3($this->MY_RIGHTS, $config);
                $modelName = 'Host';
            }
            $statusCount = $this->{$modelName}->find('count', $query);
            $this->set('config', $config);
            $this->set('statusCount', $statusCount);
            $this->viewBuilder()->setOption('serialize', ['config', 'statusCount']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $HostStatusOverviewJson->standardizedData($this->request->data);

            $this->Widget->id = (int)$this->request->data('Widget.id');;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }


        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function serviceStatusOverviewWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        $ServiceStatusOverviewJson = new ServiceStatusOverviewJson();

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new NotFoundException('Widget not found');
            }

            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $widgetId
                ]
            ]);

            $data = [];
            if ($widget['Widget']['json_data'] !== null && $widget['Widget']['json_data'] !== '') {
                $data = json_decode($widget['Widget']['json_data'], true);
            }
            $config = $ServiceStatusOverviewJson->standardizedData($data);

            if ($this->DbBackend->isNdoUtils()) {
                $query = $this->Service->getServicestatusCountBySelectedStatus($this->MY_RIGHTS, $config);
                $modelName = 'Service';
            }

            if ($this->DbBackend->isCrateDb()) {
                $query = $this->Servicestatus->getServicestatusCountBySelectedStatus($this->MY_RIGHTS, $config);
                $modelName = 'Servicestatus';
            }

            if ($this->DbBackend->isStatusengine3()) {
                $query = $this->Service->getServicestatusBySelectedStatusStatusengine3($this->MY_RIGHTS, $config);
                $modelName = 'Service';
            }
            $statusCount = $this->{$modelName}->find('count', $query);
            $this->set('config', $config);
            $this->set('statusCount', $statusCount);
            $this->viewBuilder()->setOption('serialize', ['config', 'statusCount']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $ServiceStatusOverviewJson->standardizedData($this->request->data);

            $this->Widget->id = (int)$this->request->data('Widget.id');;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['config']);
            return;
        }


        throw new MethodNotAllowedException();
    }

    /**
     * @deprecated
     */
    public function getPerformanceDataMetrics($serviceId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->Service->exists($serviceId)) {
            throw new NotFoundException();
        }

        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid'
            ],
            'conditions' => [
                'Service.id' => $serviceId,
            ],
        ]);


        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
        $ServicestatusFields->perfdata();
        $servicestatus = $this->Servicestatus->byUuid($service['Service']['uuid'], $ServicestatusFields);

        if (!empty($servicestatus)) {
            $PerfdataParser = new PerfdataParser($servicestatus['Servicestatus']['perfdata']);
            $this->set('perfdata', $PerfdataParser->parse());
            $this->viewBuilder()->setOption('serialize', ['perfdata']);
            return;
        }
        $this->set('perfdata', []);
        $this->viewBuilder()->setOption('serialize', ['perfdata']);
    }
}
