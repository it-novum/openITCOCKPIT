<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
use itnovum\openITCOCKPIT\Core\SessionCache;

/**
 * Class AngularController
 * @property  Host Host
 * @property Service Service
 * @property AppAuthComponent Auth
 * @property MenuComponent Menu
 */
class AngularController extends AppController {

    public $layout = 'blank';

    public $components = [
        'Session'
    ];

    public function paginator() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function mass_delete() {
        return;
    }

    public function confirm_delete() {
        return;
    }

    public function export(){
        return;
    }

    public function user_timezone() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $userTimezone = $this->Auth->user('timezone');
        if (strlen($userTimezone) < 2) {
            $userTimezone = 'Europe/Berlin';
        }
        $UserTime = new DateTime($userTimezone);
        $ServerTime = new DateTime();

        $timezone = [
            'user_timezone'          => $userTimezone,
            'user_offset'            => $UserTime->getOffset(),
            'server_time_utc'        => time(),
            'server_time'            => date('F d, Y H:i:s'),
            'server_timezone_offset' => $ServerTime->getOffset()
        ];
        $this->set('timezone', $timezone);
        $this->set('_serialize', ['timezone']);
    }

    public function version_check() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $path = APP . 'Lib' . DS . 'AvailableVersion.php';
        $availableVersion = '???';
        if (file_exists($path)) {
            require_once $path;
            $availableVersion = openITCOCKPIT_AvailableVersion::get();
        }
        Configure::load('version');
        $newVersionAvailable = false;
        if (version_compare($availableVersion, Configure::read('version')) > 0 && $this->hasRootPrivileges) {
            $newVersionAvailable = true;
        }

        $this->set('newVersionAvailable', $newVersionAvailable);
        $this->set('_serialize', ['newVersionAvailable']);
    }

    public function menustats() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }


        $showstatsinmenu = (bool)$this->Auth->user('showstatsinmenu');
        $hoststatusCount = [
            '1' => 0,
            '2' => 0,
        ];
        $servicestatusCount = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
        ];

        if ($showstatsinmenu) {
            //Load stats overview for this user
            $this->loadModel('Host');
            $hoststatusCountResult = $this->Host->find('all', [
                'conditions' => [
                    'Host.disabled'                  => 0,
                    'HostObject.is_active'           => 1,
                    'HostsToContainers.container_id' => $this->MY_RIGHTS,
                    'Hoststatus.current_state >'     => 0,
                ],
                'contain'    => [],
                'fields'     => [
                    'Hoststatus.current_state',
                    'COUNT(DISTINCT Hoststatus.host_object_id) AS count',
                ],
                'group'      => [
                    'Hoststatus.current_state',
                ],
                'joins'      => [

                    [
                        'table'      => 'nagios_objects',
                        'type'       => 'INNER',
                        'alias'      => 'HostObject',
                        'conditions' => 'Host.uuid = HostObject.name1 AND HostObject.objecttype_id = 1',
                    ],

                    [
                        'table'      => 'nagios_hoststatus',
                        'type'       => 'INNER',
                        'alias'      => 'Hoststatus',
                        'conditions' => 'Hoststatus.host_object_id = HostObject.object_id',
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
            ]);
            foreach ($hoststatusCountResult as $hoststatus) {
                $hoststatusCount[$hoststatus['Hoststatus']['current_state']] = (int)$hoststatus[0]['count'];
            }

            $this->loadModel('Service');
            $servicestatusCountResult = $this->Host->find('all', [
                'conditions' => [
                    'Service.disabled'               => 0,
                    'Servicestatus.current_state >'  => 0,
                    'ServiceObject.is_active'        => 1,
                    'HostsToContainers.container_id' => $this->MY_RIGHTS,

                ],
                'contain'    => [],
                'fields'     => [
                    'Servicestatus.current_state',
                    'COUNT(DISTINCT Servicestatus.service_object_id) AS count',
                ],
                'group'      => [
                    'Servicestatus.current_state',
                ],
                'joins'      => [
                    [
                        'table'      => 'hosts_to_containers',
                        'type'       => 'INNER',
                        'alias'      => 'HostsToContainers',
                        'conditions' => 'HostsToContainers.host_id = Host.id',
                    ],
                    [
                        'table'      => 'services',
                        'type'       => 'INNER',
                        'alias'      => 'Service',
                        'conditions' => 'Service.host_id = Host.id',
                    ],
                    [
                        'table'      => 'nagios_objects',
                        'type'       => 'INNER',
                        'alias'      => 'ServiceObject',
                        'conditions' => 'ServiceObject.name2 = Service.uuid',
                    ],
                    [
                        'table'      => 'nagios_servicestatus',
                        'type'       => 'INNER',
                        'alias'      => 'Servicestatus',
                        'conditions' => 'Servicestatus.service_object_id = ServiceObject.object_id',
                    ],
                ],
            ]);
            foreach ($servicestatusCountResult as $servicestatus) {
                $servicestatusCount[$servicestatus['Servicestatus']['current_state']] = (int)$servicestatus[0]['count'];
            }
        }
        $this->set(compact(['showstatsinmenu', 'hoststatusCount', 'servicestatusCount']));
        $this->set('_serialize', ['showstatsinmenu', 'hoststatusCount', 'servicestatusCount']);
    }

    public function menu() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $SessionCache = new SessionCache('Menu', $this->Session, 300);
        if (!$SessionCache->has('items')) {
            $menu = $this->Menu->compileMenu();
            $menu = $this->Menu->filterMenuByAcl($menu, $this->PERMISSIONS, true);
            $menu = $this->Menu->forAngular($menu);
            $SessionCache->set('items', $menu);
        }

        $menu = $SessionCache->get('items');

        $this->set('menu', $menu);
        $this->set('_serialize', ['menu']);
    }

    public function websocket_configuration(){
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $SessionCache = new SessionCache('Systemsettings', $this->Session, 600);
        if ($SessionCache->isEmpty()) {
            $SessionCache->set('systemsettings', $this->Systemsetting->findAsArray());
        }

        $systemsettings = $SessionCache->get('systemsettings');
        $websocketConfig = $systemsettings['SUDO_SERVER'];
        $websocketConfig['SUDO_SERVER.URL'] = 'wss://' . env('HTTP_HOST') . '/sudo_server';

        $this->set('websocket', $websocketConfig);
        $this->set('_serialize', ['websocket']);
    }

    public function not_found(){
        $this->layout = 'angularjs';
        //Only ship HTML template
        return;
    }

    public function nested_list(){
        //Only ship HTML template
        return;
    }

}
