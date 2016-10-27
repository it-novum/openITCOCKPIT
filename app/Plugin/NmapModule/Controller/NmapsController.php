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


App::uses('AppController', 'Controller');

class NmapsController extends AppController {
    public $layout = 'Admin.default';
    public $components = ['Paginator', 'ListFilter.ListFilter'];
    public $helpers = ['ListFilter.ListFilter'];
    public $uses = [

        'NmapModule.NmapTemplate',
        'NmapModule.NmapScan',
        'Host',
        'NmapModule.Nmap',
        'Container',
        'Servicetemplate'
    ];

    private $options = array('nmap_binary' => 'nmap');

    public function nmapsList(){
        //Define the target and options

        $target = array('172.16.92.100');


        try {
            //$nmap = new Net_Nmap($options);

            $this->Nmap->setOptions($this->options);
            $nmap_options = array(
                'host-timeout' => '10m',
                'os_detection' => false,
                'fast_scan' => true,
                'service_info' => true,
                'all_options' => false,
                'port_ranges' => '1-65535', // Only specified ports
            );

            $this->Nmap->enableOptions($nmap_options);

            // Scan
            $res = $this->Nmap->scan($target);

            // Get failed hosts
            $failed_to_resolve = $this->Nmap->getFailedToResolveHosts();
            if (count($failed_to_resolve) > 0) {
                echo 'Failed to resolve given hostname/IP: ' .
                    implode (', ', $failed_to_resolve) .
                    "\n";
            }

            //Parse XML Output to retrieve Hosts Object

            $hosts = $this->Nmap->parseXMLOutput();
            $this->set('hosts', $hosts);

        } catch (NmapException $ne) {
            echo $ne->getMessage();
        }
    }

    public function scanHost($hostId) {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);

        $userId = $this->Auth->user('id');

        if(!$this->Host->exists($hostId)){
            throw new NotFoundException(__('Invalid host'));
        }
        $_host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $hostId,
            ],
            'contain' => [
                'Container'
            ],
            'fields' => [
                'Host.container_id',
                'Host.address',
                'Container.*'
            ]
        ]);

        $containerIdsToCheck = Hash::extract($_host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $_host['Host']['container_id'];
        if(!$this->allowedByContainerId($containerIdsToCheck)){
            $this->render403();
            return;
        }

        try {
            $this->Nmap->setOptions($this->options);
            $nmap_options = array(
                'host-timeout' => '10m',
                'os_detection' => false,
                'fast_scan' => false,
                'service_info' => false,
                'all_options' => false,
                'port_ranges' => '1-65535',
            );
            $this->Nmap->enableOptions($nmap_options);

            $target = [$_host['Host']['address']];
            $res = $this->Nmap->scan($target);

            $hosts = $this->Nmap->parseXMLOutput();
            echo '<pre>';
            var_dump($this->NmapScan->newGroup());
            echo '</pre>';
            foreach ($hosts[0]->getServices() as $hostItem) {
                echo '<pre>';
                var_dump($hostItem);
                echo '</pre>';

            }
            exit;
            $this->set('result', $hosts);
            $this->set('hostId', $_host['Host']['id']);

        } catch (NmapException $ne) {
            $this->setFlash($ne->getMessage(), false);
            $this->redirect('/hosts/index');
        }

    }
    
    public function index(){
        $options = [
            'order' => [
                'Container.name' => 'asc'
            ],
            'conditions' => [
                'Container.parent_id' => $this->MY_RIGHTS
            ],
        ];

        $query = Hash::merge($this->Paginator->settings, $options);

        if($this->isApiRequest()){
            unset($query['limit']);
            $allNmaps = $this->NmapTemplate->find('all', $query);
        }else{
            $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
            $allNmaps = $this->Paginator->paginate();
        }

        $this->set('allNmaps', $allNmaps);
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_nmaps']);
        $this->set('isFilter', false);
        if(isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null){
            $this->set('isFilter', true);
        }
    }

    public function add(){
        if($this->hasRootPrivileges === true){
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }else{
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }

        $servicetemplates = [];
        if($this->request->is('post') || $this->request->is('put')){
            if($this->NmapTemplate->saveAll($this->request->data)){
                if($this->request->ext == 'json'){
                    $this->serializeId();
                    return;
                }
                $this->setFlash(__('<a href="/nmap_module/nmaps/edit/%s">Nmap Template</a> successfully saved', $this->NmapTemplate->id));
                $this->redirect(array('action' => 'index'));
            }
            if($this->request->ext == 'json'){
                $this->serializeErrorMessage();
                return;
            }
            $this->setFlash(__('Nmap Template could not be saved'), false);
            if(isset($this->request->data['NmapTemplate']['container_id'])){
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['NmapTemplate']['container_id']);
                $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerIds, 'list');
            }
        }

        $this->set(compact(['containers', 'servicetemplates']));
        $this->set('_serialize', ['containers', 'servicetemplates']);
    }

    public function edit($id = null){
        if(!$this->NmapTemplate->exists($id)) {
            throw new NotFoundException(__('Invalid Nmap Template'));
        }

        if($this->hasRootPrivileges === true){
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }else{
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_CONTACTGROUP, [], $this->hasRootPrivileges);
        }

        $nmapTemplate = $this->NmapTemplate->findById($id);
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($nmapTemplate['NmapTemplate']['container_id']);
        $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerIds, 'list');

        if(!$this->allowedByContainerId(Hash::extract($nmapTemplate, 'Container.parent_id'))){
            $this->render403();
            return;
        }

        if($this->request->is('post') || $this->request->is('put')){
            $this->request->data['NmapTemplate']['id'] = $nmapTemplate['NmapTemplate']['id'];
            if($this->NmapTemplate->saveAll($this->request->data)){
                if($this->request->ext == 'json'){
                    $this->serializeId();
                    return;
                }
                $this->setFlash(__('<a href="/nmap_module/nmaps/edit/%s">Nmap Template</a> successfully updated', $this->NmapTemplate->id));
                $this->redirect(array('action' => 'index'));
            }
            if($this->request->ext == 'json'){
                $this->serializeErrorMessage();
                return;
            }
            $this->setFlash(__('Nmap Template could not be saved'), false);
            if(isset($this->request->data['NmapTemplate']['container_id'])){
                $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->request->data['NmapTemplate']['container_id']);
                $servicetemplates = $this->Servicetemplate->servicetemplatesByContainerId($containerIds, 'list');
            }
        }else{
            $this->request->data = $nmapTemplate;
        }

        $this->set(compact(['containers', 'servicetemplates']));
        $this->set('_serialize', ['containers', 'servicetemplates']);
    }

    public function delete($id = null){
        if(!$this->NmapTemplate->exists($id)){
            throw new NotFoundException(__('Invalid Nmap Template'));
        }

        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }

        $NmapTemplate = $this->NmapTemplate->findById($id);
        if(!$this->allowedByContainerId(Hash::extract($NmapTemplate, 'Container.parent_id'))){
            $this->render403();
            return;
        }

        $this->NmapTemplate->id = $id;
        if($this->NmapTemplate->delete()){
            $this->setFlash(__('Nmap Template deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->setFlash(__('Could not delete Nmap Template'), false);
        $this->redirect(array('action' => 'index'));
    }

    public function monitor($id = null){
        $getParams = func_get_args();
        if(!isset($getParams[0]) || count($getParams) < 2){
            $this->setFlash(__('Please select one or more items.'));
            $this->redirect('/hosts/index');
        }
        $hostId = $getParams[0];
        unset($getParams[0]);

        if(!$this->Host->exists($hostId)){
            throw new NotFoundException(__('Invalid host'));
        }
        $_host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $hostId,
            ],
            'contain' => [
                'Container'
            ],
            'fields' => [
                'Host.container_id',
                'Host.address',
                'Container.*'
            ]
        ]);

        $containerIdsToCheck = Hash::extract($_host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $_host['Host']['container_id'];
        if(!$this->allowedByContainerId($containerIdsToCheck)){
            $this->render403();
            return;
        }


        echo '<pre>';
        var_dump(func_get_args());
        echo '</pre>';
        exit;
    }
}