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

use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\RFCRouter;


class SearchController extends AppController {
    public $layout = 'Admin.default';
    public $uses = ['Host', 'Service', 'Customvariable'];

    public function index() {

        if ($this->request->is('post') || $this->request->is('put')) {
            //Default search
            if (isset($this->request->data['SearchDefault'])) {
                if (empty($this->request->data['SearchDefault']['Servicename']) && !empty($this->request->data['SearchDefault']['Hostname'])) {
                    // The user is searching for a host, because no service name is given ;-)
                    $hoststatus = [];
                    foreach ($this->request->data['Hoststatus'] as $stateId => $value) {
                        if ($value === '1') {
                            $hoststatus[$stateId] = 1;
                        }
                    }

                    $url = RFCRouter::queryString([
                        'filter'    => [
                            'Hoststatus.current_state' => $hoststatus,
                            'Host.name'                => $this->request->data['SearchDefault']['Hostname'],
                        ],
                        'sort'      => 'Hoststatus.last_state_change',
                        'direction' => 'desc'
                    ]);

                    return $this->redirect(sprintf('/hosts/index%s', $url));


                } else if (!empty($this->request->data['SearchDefault']['Servicename']) && empty($this->request->data['SearchDefault']['Hostname'])) {
                    // The user typed in a service name but not a host name, so we need to search for the service

                    $servicestatus = [];
                    foreach ($this->request->data['Servicestatus'] as $stateId => $value) {
                        if ($value === '1') {
                            $servicestatus[$stateId] = 1;
                        }
                    }

                    $url = RFCRouter::queryString([
                        'filter'    => [
                            'Servicestatus.current_state' => $servicestatus,
                            'Service.servicename'         => $this->request->data['SearchDefault']['Servicename']
                        ],
                        'sort'      => 'Servicestatus.last_state_change',
                        'direction' => 'desc'
                    ]);

                    return $this->redirect(sprintf('/services/index%s', $url));
                } else if (!empty($this->request->data['SearchDefault']['Servicename']) && !empty($this->request->data['SearchDefault']['Hostname'])) {
                    // The user entered a service and a host name, so we need to search for host and service
                    $servicestatus = [];
                    foreach ($this->request->data['Servicestatus'] as $stateId => $value) {
                        if ($value === '1') {
                            $servicestatus[$stateId] = 1;
                        }
                    }

                    $url = RFCRouter::queryString([
                        'filter'    => [
                            'Servicestatus.current_state' => $servicestatus,
                            'Service.servicename'         => $this->request->data['SearchDefault']['Servicename'],
                            'Host.name'                   => $this->request->data['SearchDefault']['Hostname'],
                        ],
                        'sort'      => 'Servicestatus.last_state_change',
                        'direction' => 'desc'
                    ]);

                    return $this->redirect(sprintf('/services/index%s', $url));
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
                        $url['Filter.Host.keywords[' . $key . ']'] = $keyword;
                    }
                    $url['q'] = 1; //Fix for .exe .png and so on
                    return $this->redirect($url);
                }

                if (!empty($this->request->data['SearchKeywords']['Servicekeywords'])) {
                    //The user search for a service keyword
                    $hostKeywords = explode(',', $this->request->data['SearchKeywords']['Servicekeywords']);
                    $url = ['controller' => 'services', 'action' => 'index'];
                    foreach ($hostKeywords as $key => $keyword) {
                        $url['Filter.Service.keywords[' . $key . ']'] = $keyword;
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
                $url = RFCRouter::queryString([
                    'filter'    => [
                        'Host.address' => $this->request->data['SearchAddress']['Hostaddress'],
                    ],
                    'sort'      => 'Hoststatus.last_state_change',
                    'direction' => 'desc'
                ]);

                return $this->redirect(sprintf('/hosts/index%s', $url));
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

    public function hostMacro($macroname = '') {
        $result = $this->Customvariable->find('all', [
            'conditions' => [
                'Customvariable.objecttype_id' => OBJECT_HOST,
                'Customvariable.name LIKE'     => '%' . $macroname . '%',
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

    public function serviceMacro($macroname = '') {
        $result = $this->Customvariable->find('all', [
            'conditions' => [
                'Customvariable.objecttype_id' => OBJECT_SERVICE,
                'Customvariable.name LIKE'     => '%' . $macroname . '%',
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
