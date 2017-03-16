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
class BrowsersController extends AppController {

    public $layout = 'Admin.default';
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

    function index() {
        $top_node = $this->Container->findById(ROOT_CONTAINER);
        $parents = $this->Container->getPath($top_node['Container']['parent_id']);

        $tenants = $this->__getTenants();

        //$recursive = true;
        $recursive = $this->Auth->user('recursive_browser');

        $hostQuery = $this->Browser->hostsQuery(ROOT_CONTAINER);
        $serviceQuery = $this->Browser->serviceQuery(ROOT_CONTAINER);
        if ($recursive) {
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
    }


    function tenantBrowser($id) {
        if (!$this->Container->exists($id)) {
            throw new NotFoundException(__('Invalid container'));
        }
        $MY_RIGHTS_WITH_TENANT = array_merge($this->MY_RIGHTS, array_keys($this->__getTenants()));

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
            $hosts = $this->Paginator->paginate('Host');
            //$hosts = $this->Host->find('all', $query);
            $query = $this->Browser->serviceQuery($lookupIds);
            $services = $this->Service->find('all', $query);
        }
        $state_array_host = $this->Browser->countHoststate($hosts);
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

    public function refreshBrowser(){
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }

        $currentFlag = $this->Auth->user('recursive_browser');
        if($this->request->is('post') || $this->request->is('put')){
            $this->User->id = $this->Auth->user('id');
            /*if($this->User->saveField('recursive_browser',$recursive)){
                $this->setFlash(__('Recursive flag saved'));
                return $this->redirect(['action' => 'index']);
            }
            $this->setFlash(__('Recursive flag could not be saved'), false);
            return $this->redirect(['action' => 'index']);*/
        }
    }

    protected function __getTenants() {
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
