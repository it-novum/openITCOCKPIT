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
use App\Model\Table\UsersTable;
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
use Cake\ORM\Locator\LocatorAwareTrait;

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

    /*
    //Most calls are API calls or modal html requests
    //Blank is the best default for Dashboards...
    public $layout = 'blank';

    public $uses = [
        'DashboardTab',
        'Widget',
        'Parenthost',
        MONITORING_HOSTSTATUS,
        'User',
        MONITORING_SERVICESTATUS,
        'Service',
        'Host',
        'Systemsetting'
    ];*/

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

        //Check if a tab exists for the given user
        if ($this->DashboardTab->hasUserATab($User->getId()) === false) {
            $result = $this->DashboardTab->createNewTab($User->getId());
            if ($result) {
                //Create default widgets
                $result['Widget'] = $this->Widget->getDefaultWidgets($this->PERMISSIONS);
                $this->DashboardTab->saveAll($result);
            }
        }
        $tabs = $this->DashboardTab->getAllTabsByUserId($User->getId());

        $widgets = $this->Widget->getAvailableWidgets($this->PERMISSIONS);

        $this->set('tabs', $tabs);
        $this->set('widgets', $widgets);
        $this->set('tabRotationInterval', $tabRotationInterval);
        $this->set('_serialize', ['tabs', 'widgets', 'tabRotationInterval']);
    }

    public function getWidgetsForTab($tabId) {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        if (!$this->DashboardTab->exists($tabId)) {
            throw new NotFoundException(sprintf('Tab width id %s not found', $tabId));
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $widgets = $this->DashboardTab->getWidgetsForTabByUserIdAndTabId($User->getId(), $tabId);

        $this->set('widgets', $widgets);
        $this->set('_serialize', ['widgets']);
    }

    public function dynamicDirective() {
        $directiveName = $this->request->query('directive');

        $widgets = $this->Widget->getAvailableWidgets($this->PERMISSIONS);
        $isValidDirective = false;
        foreach($widgets as $widget){
            if($widget['directive'] === $directiveName){
                $isValidDirective = true;
                break;
            }
        }

        if(!$isValidDirective){
            throw new ForbiddenException();
        }

        if (strlen($directiveName) < 2) {
            throw new RuntimeException('Wrong AngularJS directive name?');
        }
        $this->set('directiveName', $directiveName);
    }

    public function saveGrid() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Widget->saveAll($this->request->data)) {

            //Update DashboardTab last modified
            if (isset($this->request->data[0]['Widget']['dashboard_tab_id'])) {
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $tab = $this->DashboardTab->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'DashboardTab.id'      => $this->request->data[0]['Widget']['dashboard_tab_id'],
                        'DashboardTab.user_id' => $User->getId()
                    ]
                ]);

                if (!empty($tab)) {
                    $tab['DashboardTab']['modified'] = date('Y-m-d H:i:s');
                    $this->DashboardTab->save($tab);
                }
            }


            $this->set('message', __('Successfully saved'));
            $this->set('_serialize', ['message']);
            return;
        }
        $this->serializeErrorMessageFromModel('Widget');
    }

    public function addWidgetToTab() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!isset($this->request->data['Widget']['typeId']) || !isset($this->request->data['Widget']['dashboard_tab_id'])) {
            throw new RuntimeException('Missing parameter typeId || dashboard_tab_id');
        }

        $this->request->data['Widget']['dashboard_tab_id'] = (int)$this->request->data['Widget']['dashboard_tab_id'];

        if (!$this->DashboardTab->exists($this->request->data['Widget']['dashboard_tab_id'])) {
            throw new NotFoundException('DashboardTab does not exists!');
        }

        if (!$this->Widget->isWidgetAvailable($this->request->data['Widget']['typeId'], $this->PERMISSIONS)) {
            throw new NotFoundException('Widget not found!');
        }

        $widget = $this->Widget->getWidgetByTypeId($this->request->data['Widget']['typeId'], $this->PERMISSIONS);
        $data = [
            'dashboard_tab_id' => $this->request->data['Widget']['dashboard_tab_id'],
            'type_id'          => $this->request->data['Widget']['typeId'],
            'row'              => 0,
            'col'              => 0,
            'width'            => $widget['width'],
            'height'           => $widget['height'],
            'title'            => $widget['title'],
            'icon'             => $widget['icon'],
            'color'            => 'jarviswidget-color-blueDark',
            'directive'        => $widget['directive']
        ];

        $this->Widget->create();
        if ($this->Widget->save($data)) {
            $widget = $this->Widget->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Widget.id' => $this->Widget->id
                ]
            ]);
            $this->set('message', __('Successfully saved'));
            $this->set('widget', $widget);
            $this->set('_serialize', ['message', 'widget']);
            return;
        }
        $this->serializeErrorMessageFromModel('Widget');
    }

    public function removeWidgetFromTab() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        if (!isset($this->request->data['Widget']['id']) || !isset($this->request->data['Widget']['dashboard_tab_id'])) {
            throw new RuntimeException('Missing parameter id || dashboard_tab_id');
        }

        $this->request->data['Widget']['id'] = (int)$this->request->data['Widget']['id'];

        if (!$this->Widget->exists($this->request->data['Widget']['id'])) {
            throw new NotFoundException('Widget does not exists!');
        }
        if ($this->Widget->delete($this->request->data['Widget']['id'])) {
            $this->set('message', __('Successfully deleted'));
            $this->set('_serialize', ['message', 'widget']);
            return;
        }
        $this->serializeErrorMessageFromModel('Widget');
    }

    public function saveTabOrder() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $newOrder = $this->request->data('order');
        if (empty($newOrder) || !is_array($newOrder)) {
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $success = true;

        foreach ($newOrder as $key => $tabId) {
            $tab = $this->DashboardTab->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'DashboardTab.id'      => $tabId,
                    'DashboardTab.user_id' => $User->getId()
                ]
            ]);

            if (!empty($tab)) {
                $tab['DashboardTab']['position'] = (int)$key;
                if (!$this->DashboardTab->save($tab)) {
                    $success = false;
                }
            }
        }

        if ($success === false) {
            $this->response->statusCode(400);
        }

        $this->set('success', $success);
        $this->set('_serialize', ['success']);
    }

    function addNewTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $name = $this->request->data('DashboardTab.name');
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $result = $this->DashboardTab->createNewTab($User->getId(), [
            'name' => $name
        ]);

        if ($result) {
            $this->set('DashboardTab', $result);
            $this->set('_serialize', ['DashboardTab']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    public function saveTabRotateInterval() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $tabRotationInterval = (int)$this->request->data('User.dashboard_tab_rotation');

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        $this->User->id = $User->getId();
        $this->User->saveField('dashboard_tab_rotation', $tabRotationInterval);

        $this->set('success', true);
        $this->set('_serialize', ['success']);
    }

    public function renameDashboardTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        $name = $this->request->data('DashboardTab.name');
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

        $dashboardTab['DashboardTab']['name'] = $name;
        if ($this->DashboardTab->save($dashboardTab)) {
            $this->set('DashboardTab', [
                'DashboardTab' => $dashboardTab
            ]);

            $this->set('_serialize', ['DashboardTab']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }


    public function deleteDashboardTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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

        if ($this->DashboardTab->delete($id)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    public function startSharing() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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

        $dashboardTab['DashboardTab']['shared'] = 1;

        if ($this->DashboardTab->save($dashboardTab)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    public function stopSharing() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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

        $dashboardTab['DashboardTab']['shared'] = 0;

        if ($this->DashboardTab->save($dashboardTab)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    public function getSharedTabs() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $tabs = $this->DashboardTab->getSharedTabs();

        $this->set('tabs', $tabs);
        $this->set('_serialize', ['tabs']);
    }

    public function createFromSharedTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        $id = (int)$this->request->data('DashboardTab.id');

        $sourceTabWithWidgets = $this->DashboardTab->find('first', [
            'conditions' => [
                'DashboardTab.id'     => $id,
                'DashboardTab.shared' => 1
            ]
        ]);

        if (empty($sourceTabWithWidgets)) {
            throw new NotFoundException();
        }

        $copyTabWithWidgets = $sourceTabWithWidgets;

        unset($copyTabWithWidgets['DashboardTab']['id']);
        $copyTabWithWidgets['DashboardTab']['user_id'] = $User->getId();
        $copyTabWithWidgets['DashboardTab']['position'] = $this->DashboardTab->getNextPosition($User->getId());
        $copyTabWithWidgets['DashboardTab']['shared'] = 0;
        $copyTabWithWidgets['DashboardTab']['source_tab_id'] = $id;
        $copyTabWithWidgets['DashboardTab']['check_for_updates'] = 1;
        $copyTabWithWidgets['DashboardTab']['last_update'] = time();

        foreach ($copyTabWithWidgets['Widget'] as $key => $widgetData) {
            unset($copyTabWithWidgets['Widget'][$key]['id']);
            unset($copyTabWithWidgets['Widget'][$key]['dashboard_tab_id']);
        }

        $this->DashboardTab->create();
        if ($this->DashboardTab->saveAll($copyTabWithWidgets)) {
            $newCreatedDashboardTab = $this->DashboardTab->find('first', [
                'conditions' => [
                    'DashboardTab.id'      => $this->DashboardTab->id,
                    'DashboardTab.user_id' => $User->getId()
                ]
            ]);

            $newCreatedDashboardTab['DashboardTab']['id'] = (int)$newCreatedDashboardTab['DashboardTab']['id'];
            $newCreatedDashboardTab['DashboardTab']['user_id'] = (int)$newCreatedDashboardTab['DashboardTab']['user_id'];
            $newCreatedDashboardTab['DashboardTab']['position'] = (int)$newCreatedDashboardTab['DashboardTab']['position'];
            $newCreatedDashboardTab['DashboardTab']['shared'] = (bool)$newCreatedDashboardTab['DashboardTab']['shared'];
            $newCreatedDashboardTab['DashboardTab']['source_tab_id'] = (int)$newCreatedDashboardTab['DashboardTab']['source_tab_id'];
            $newCreatedDashboardTab['DashboardTab']['check_for_updates'] = (bool)$newCreatedDashboardTab['DashboardTab']['check_for_updates'];
            $newCreatedDashboardTab['DashboardTab']['last_update'] = (int)$newCreatedDashboardTab['DashboardTab']['last_update'];
            $newCreatedDashboardTab['DashboardTab']['locked'] = (bool)$newCreatedDashboardTab['DashboardTab']['locked'];

            $this->set('DashboardTab', $newCreatedDashboardTab);
            $this->set('_serialize', ['DashboardTab']);
            return;
        }

        $this->serializeErrorMessageFromModel('DashboardTab');
    }

    public function checkForUpdates() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $tabId = (int)$this->request->query('tabId');
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        if (!$this->DashboardTab->exists($tabId)) {
            throw new NotFoundException('DashboardTab not found');
        }

        $tab = $this->DashboardTab->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'DashboardTab.id'      => $tabId,
                'DashboardTab.user_id' => $User->getId()
            ]
        ]);

        $sourceTab = $this->DashboardTab->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'DashboardTab.id' => $tab['DashboardTab']['source_tab_id']
            ]
        ]);

        $updateAvailable = false;
        if (!empty($sourceTab) && !empty($tab)) {
            if (strtotime($sourceTab['DashboardTab']['modified']) > $tab['DashboardTab']['last_update']) {
                $updateAvailable = true;
            }
        }

        $this->set('updateAvailable', $updateAvailable);
        $this->set('_serialize', ['updateAvailable']);
    }

    public function neverPerformUpdates() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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

        $dashboardTab['DashboardTab']['check_for_updates'] = 0;

        if ($this->DashboardTab->save($dashboardTab)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    public function updateSharedTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        $id = (int)$this->request->data('DashboardTab.id');

        $tabToUpdate = $this->DashboardTab->find('first', [
            'conditions' => [
                'DashboardTab.id'      => $id,
                'DashboardTab.user_id' => $User->getId()
            ]
        ]);

        $sourceTabWithWidgets = $this->DashboardTab->find('first', [
            'conditions' => [
                'DashboardTab.id' => $tabToUpdate['DashboardTab']['source_tab_id']
            ]
        ]);

        if (empty($sourceTabWithWidgets) || empty($tabToUpdate)) {
            throw new NotFoundException();
        }

        $tabToUpdate['DashboardTab']['last_update'] = time();
        $tabToUpdate['DashboardTab']['locked'] = (bool)$sourceTabWithWidgets['DashboardTab']['locked'];
        $tabToUpdate['Widget'] = $sourceTabWithWidgets['Widget'];

        foreach ($tabToUpdate['Widget'] as $key => $widgetData) {
            unset($tabToUpdate['Widget'][$key]['id']);
            unset($tabToUpdate['Widget'][$key]['dashboard_tab_id']);
        }

        //Delete old widgets
        $this->Widget->deleteAll([
            'Widget.dashboard_tab_id' => $id
        ]);

        if ($this->DashboardTab->saveAll($tabToUpdate)) {
            $tabToUpdate = $this->DashboardTab->find('first', [
                'conditions' => [
                    'DashboardTab.id'      => $id,
                    'DashboardTab.user_id' => $User->getId()
                ]
            ]);

            $newCreatedDashboardTab['DashboardTab']['id'] = (int)$tabToUpdate['DashboardTab']['id'];
            $newCreatedDashboardTab['DashboardTab']['user_id'] = (int)$tabToUpdate['DashboardTab']['user_id'];
            $newCreatedDashboardTab['DashboardTab']['position'] = (int)$tabToUpdate['DashboardTab']['position'];
            $newCreatedDashboardTab['DashboardTab']['shared'] = (bool)$tabToUpdate['DashboardTab']['shared'];
            $newCreatedDashboardTab['DashboardTab']['source_tab_id'] = (int)$tabToUpdate['DashboardTab']['source_tab_id'];
            $newCreatedDashboardTab['DashboardTab']['check_for_updates'] = (bool)$tabToUpdate['DashboardTab']['check_for_updates'];
            $newCreatedDashboardTab['DashboardTab']['last_update'] = (int)$tabToUpdate['DashboardTab']['last_update'];
            $newCreatedDashboardTab['DashboardTab']['locked'] = (bool)$tabToUpdate['DashboardTab']['locked'];

            $this->set('DashboardTab', $tabToUpdate);
            $this->set('_serialize', ['DashboardTab']);
            return;
        }

        $this->serializeErrorMessageFromModel('DashboardTab');
    }

    public function renameWidget() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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
            $this->set('_serialize', ['success']);
            return;
        }

        $this->serializeErrorMessageFromModel('Widget');
    }

    public function lockOrUnlockTab() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    public function restoreDefault() {
        if (!$this->request->is('post') || !$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

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
            $this->set('_serialize', ['success']);
            return;
        }
        $this->serializeErrorMessageFromModel('DashboardTab');
        return;
    }

    /***** Basic Widgets *****/
    public function welcomeWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
    }

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
        $this->set('_serialize', ['parent_outages']);
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
            $this->set('_serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $HostStatusListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

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
            $this->set('_serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $DowntimeHostListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

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
            $this->set('_serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $DowntimeServiceListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

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
            $this->set('_serialize', ['config']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $ServiceStatusListJson->standardizedData($this->request->data);

            $this->Widget->id = $widgetId;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
            return;
        }

        throw new MethodNotAllowedException();
    }

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

            $this->set('_serialize', ['config', 'htmlContent']);
            return;
        }


        if ($this->request->is('post')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
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
                $this->set('_serialize', ['serviceId']);
                return;
            }
            $this->response->statusCode(400);
            return;
        }
        throw new MethodNotAllowedException();
    }


    public function trafficLightWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $TrafficlightJson = new TrafficlightJson();

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
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
            $this->set('_serialize', ['service', 'config', 'ACL']);
            return;
        }


        if ($this->request->is('post')) {
            $config = $TrafficlightJson->standardizedData($this->request->data);
            $widgetId = (int)$this->request->data('Widget.id');
            $serviceId = (int)$this->request->data('Widget.service_id');

            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
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
                $this->set('_serialize', ['service', 'config', 'ACL']);
                return;
            }

            $this->serializeErrorMessageFromModel('Widget');
            return;
        }
        throw new MethodNotAllowedException();
    }

    public function tachoWidget() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $TachoJson = new TachoJson();

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->query('widgetId');
            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
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
            $this->set('_serialize', ['service', 'config', 'ACL']);
            return;
        }


        if ($this->request->is('post')) {
            $config = $TachoJson->standardizedData($this->request->data);
            $widgetId = (int)$this->request->data('Widget.id');
            $serviceId = (int)$this->request->data('Widget.service_id');

            if (!$this->Widget->exists($widgetId)) {
                throw new RuntimeException('Invalid widget id');
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
                $this->set('_serialize', ['service', 'config', 'ACL']);
                return;
            }

            $this->serializeErrorMessageFromModel('Widget');
            return;
        }
        throw new MethodNotAllowedException();
    }

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
            $this->set('_serialize', ['config', 'statusCount']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $HostStatusOverviewJson->standardizedData($this->request->data);

            $this->Widget->id = (int)$this->request->data('Widget.id');;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
            return;
        }


        throw new MethodNotAllowedException();
    }

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
            $this->set('_serialize', ['config', 'statusCount']);
            return;
        }

        if ($this->request->is('post')) {
            $config = $ServiceStatusOverviewJson->standardizedData($this->request->data);

            $this->Widget->id = (int)$this->request->data('Widget.id');;
            $this->Widget->saveField('json_data', json_encode($config));

            $this->set('config', $config);
            $this->set('_serialize', ['config']);
            return;
        }


        throw new MethodNotAllowedException();
    }

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
            $this->set('_serialize', ['perfdata']);
            return;
        }
        $this->set('perfdata', []);
        $this->set('_serialize', ['perfdata']);
    }
}
