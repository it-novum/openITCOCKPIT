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

class SearchController extends AppController
{
    public $layout = 'Admin.default';
    public $uses = ['Host', 'Service', 'Customvariable'];

    public function index()
    {

        if ($this->request->is('post') || $this->request->is('put')) {
            //Default search
            if (isset($this->request->data['SearchDefault'])) {
                if (empty($this->request->data['SearchDefault']['Servicename']) && !empty($this->request->data['SearchDefault']['Hostname'])) {
                    //The user is searching for a host, because no service name is given ;-)
                    $url = ['controller' => 'hosts', 'action' => 'index'];
                    $url['Filter.Host.name'] = $this->request->data['SearchDefault']['Hostname'];
                    foreach ($this->request->data['Hoststatus'] as $state => $value) {
                        if ($value == 1) {
                            $url['Filter.Hoststatus.current_state['.$state.']'] = 1;
                        }
                    }
                    $url['q'] = 1; //Fix for .exe .png and so on
                    return $this->redirect($url);
                } elseif (!empty($this->request->data['SearchDefault']['Servicename']) && empty($this->request->data['SearchDefault']['Hostname'])) {
                    // The user typed in a service name but not a host name, so we need to search for the service
                    $url = ['controller' => 'services', 'action' => 'index'];
                    $url['Filter.Service.servicename'] = $this->request->data['SearchDefault']['Servicename'];
                    foreach ($this->request->data['Servicestatus'] as $state => $value) {
                        if ($value == 1) {
                            $url['Filter.Servicestatus.current_state['.$state.']'] = 1;
                        }
                    }
                    $url['q'] = 1; //Fix for .exe .png and so on
                    return $this->redirect(Hash::merge(['controller' => 'services', 'action' => 'index'], $url));
                } elseif (!empty($this->request->data['SearchDefault']['Servicename']) && !empty($this->request->data['SearchDefault']['Hostname'])) {
                    // The user entered a service and a host name, so we need to search for host and service
                    $url = ['controller' => 'services', 'action' => 'index'];
                    $url['Filter.Host.name'] = $this->request->data['SearchDefault']['Hostname'];
                    $url['Filter.Service.servicename'] = $this->request->data['SearchDefault']['Servicename'];
                    foreach ($this->request->data['Servicestatus'] as $state => $value) {
                        if ($value == 1) {
                            $url['Filter.Servicestatus.current_state['.$state.']'] = 1;
                        }
                    }
                    $url['q'] = 1; //Fix for .exe .png and so on
                    return $this->redirect($url);
                } else {
                    $this->setFlash(__('Invalid search query'), false);
                }
            }

            //Search by keyword
            if (isset($this->request->data['SearchKeywords'])) {
                if (!empty($this->request->data['SearchKeywords']['Hostkeywords']) && empty($this->request->data['SearchKeywords']['Servicekeywords'])) {
                    //The user search for a host keyword
                    $hostKeywords = explode(',', $this->request->data['SearchKeywords']['Hostkeywords']);
                    $url = ['controller' => 'hosts', 'action' => 'index'];
                    foreach ($hostKeywords as $key => $keyword) {
                        $url['Filter.Host.keywords['.$key.']'] = $keyword;
                    }
                    $url['q'] = 1; //Fix for .exe .png and so on
                    return $this->redirect($url);
                }

                if (!empty($this->request->data['SearchKeywords']['Servicekeywords'])) {
                    //The user search for a service keyword
                    $hostKeywords = explode(',', $this->request->data['SearchKeywords']['Servicekeywords']);
                    $url = ['controller' => 'services', 'action' => 'index'];
                    foreach ($hostKeywords as $key => $keyword) {
                        $url['Filter.Service.keywords['.$key.']'] = $keyword;
                    }
                    $url['q'] = 1; //Fix for .exe .png and so on
                    return $this->redirect($url);
                }

                $this->setFlash(__('Invalid search query'), false);
            }

            //Search object by UUID
            if (isset($this->request->data['SearchUuid']['UUID'])) {
                return $this->redirect([
                    'controller' => 'forward',
                    'action'     => 'index',
                    'uuid'       => $this->request->data['SearchUuid']['UUID'],
                    'exception'  => 'false',
                    'q'          => 1,
                ]);
                //return $this->redirect('/forward/index/uuid:'.$this->request->data['SearchUuid']['UUID']);
            }

            //Search for host address
            if (isset($this->request->data['SearchAddress']['Hostaddress'])) {
                return $this->redirect([
                    'controller'          => 'hosts',
                    'action'              => 'index',
                    'Filter.Host.address' => $this->request->data['SearchAddress']['Hostaddress'],
                    'q'                   => 1, //the last octett of the ip adress gets cut so we need an additional param
                ]);
            }

            //Search for macros
            if (isset($this->request->data['SearchMacros'])) {
                if (!empty($this->request->data['SearchMacros']['Hostmacro']) && empty($this->request->data['SearchMacros']['Servicemacro'])) {
                    //User is searching for host macros
                    return $this->redirect(['action' => 'hostMacro', 'q' => 1, $this->request->data['SearchMacros']['Hostmacro']]);
                }

                if (empty($this->request->data['SearchMacros']['Hostmacro']) && !empty($this->request->data['SearchMacros']['Servicemacro'])) {
                    //User is searching for service macros
                    return $this->redirect(['action' => 'serviceMacro', 'q' => 1, $this->request->data['SearchMacros']['Servicemacro']]);
                }

                $this->setFlash(__('Invalid search query'), false);
            }
        }

        $backUrl = $this->referer();
        $this->set(compact(['backUrl']));
    }

    public function hostMacro($macroname = '')
    {
        $result = $this->Customvariable->find('all', [
            'conditions' => [
                'Customvariable.objecttype_id' => OBJECT_HOST,
                'Customvariable.name LIKE'     => '%'.$macroname.'%',
            ],
        ]);
        $hostIds = Hash::extract($result, '{n}.Customvariable.object_id');

        $all_hosts = $this->Host->find('all', [
            'conditions' => [
                'Host.id' => $hostIds,
            ],
        ]);

        $this->set(compact('all_hosts'));

    }

    public function serviceMacro($macroname = '')
    {
        $result = $this->Customvariable->find('all', [
            'conditions' => [
                'Customvariable.objecttype_id' => OBJECT_SERVICE,
                'Customvariable.name LIKE'     => '%'.$macroname.'%',
            ],
        ]);
        $serviceIds = Hash::extract($result, '{n}.Customvariable.object_id');
        $all_services = $this->Service->find('all', [
            'conditions' => [
                'Service.id' => $serviceIds,
            ],
            'order'      => ['Host.name' => 'asc'],
        ]);
        $this->set(compact('all_services'));

    }
}