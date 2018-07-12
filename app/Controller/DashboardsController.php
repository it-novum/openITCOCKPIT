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

/**
 * Class DashboardsController
 * @property DashboardTab $DashboardTab
 * @property Widget $Widget
 */
class DashboardsController extends AppController {

    //Most calls are API calls or modal html requests
    //Blank is the best default for Dashboards...
    public $layout = 'blank';

    public $uses = [
        'Widget',
        'DashboardTab',
        'Parenthost',
        MONITORING_HOSTSTATUS
    ];

    public function index() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        //Check if a tab exists for the given user
        if ($this->DashboardTab->hasUserATab($User->getId()) === false) {
            $this->DashboardTab->createNewTab($User->getId());
        }
        $tabs = $this->DashboardTab->getAllTabsByUserId($User->getId());

        $widgets = $this->Widget->getAvailableWidgets();

        $this->set('tabs', $tabs);
        $this->set('widgets', $widgets);
        $this->set('_serialize', ['tabs', 'widgets']);
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

        if (!$this->Widget->isWidgetAvailable($this->request->data['Widget']['typeId'])) {
            throw new NotFoundException('Widget not found!');
        }

        $widget = $this->Widget->getWidgetByTypeId($this->request->data['Widget']['typeId']);
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


        $containerIds = [];
        if ($this->hasRootPrivileges === false) {
            $containerIds = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_HOST, [], $this->hasRootPrivileges, [CT_HOSTGROUP]);
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
            $query['joins'] = \Hash::merge($query['joins'], [
                [
                    'table'      => 'hosts_to_containers',
                    'alias'      => 'HostsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'HostsToContainers.host_id = Host.id',
                    ],
                ]
            ]);
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

    public function hostsPiechartWidget(){
        return;
    }

    public function hostsPiechart180Widget(){
        return;
    }


    public function servicesPiechartWidget(){
        return;
    }

    public function servicesPiechart180Widget(){
        return;
    }
}
