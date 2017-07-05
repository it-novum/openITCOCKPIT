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
 * @property Currentstatereport $Currentstatereport
 * @property Host               $Host
 * @property Service            $Service
 * @property Hoststatus         $Hoststatus
 */
class CurrentstatereportsController extends AppController
{
    public $layout = 'Admin.default';
    public $uses = [
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Currentstatereport',
        'Host',
        'Service',
    ];

    public function index()
    {
        $userContainerId = $this->Auth->user('container_id');
        $currentStateData = [];
        $serviceStatusExists = false;
        //ContainerID => 1 for ROOT Container

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS, $this->hasRootPrivileges);
        $services = Hash::combine($this->Service->servicesByHostContainerIds($containerIds),
            '{n}.Service.id', '{n}'
        );
        $selectedServices =
        $this->set(compact(['services', 'userContainerId']));

        if ($this->request->is('post') || $this->request->is('put')) {
            $selectedServices = [];
            $conditions = $this->request->data('Currentstatereport.current_state');
            if(sizeof($conditions) === 4){
                $conditions = []; //consider all states
            }
            $this->Currentstatereport->set($this->request->data);
            if ($this->Currentstatereport->validates()) {

                $serviceUuids = [];
                $hostUuids = [];
                $serviceIds = [];
                if(!empty($this->request->data('Currentstatereport.Service'))){
                    foreach ($this->request->data('Currentstatereport.Service') as $serviceId) {
                        if(!empty($services[$serviceId])){
                            $serviceUuids[] = $services[$serviceId]['Service']['uuid'];
                            $hostUuids[] = $services[$serviceId]['Host']['uuid'];
                            $serviceIds[] = $serviceId;
                        }
                    }
                }
                $selectedServices = $this->Service->find('all', [
                    'recursive' => -1,
                    'contain' => [
                        'Host' => [
                            'fields' => [
                                'Host.uuid',
                                'Host.id',
                                'Host.name',
                                'Host.description',
                                'Host.address'
                            ]
                        ],
                        'Servicetemplate' => [
                            'fields' => [
                                'Servicetemplate.name'
                            ]
                        ]
                    ],
                    'conditions' => [
                        'Service.id' => $serviceIds
                    ],
                    'fields' => [
                        'Service.id',
                        'Service.uuid',
                        'Service.name'
                    ]
                ]);
                $hostUuids = array_unique($hostUuids);
                $currentServiceStateData = $this->Servicestatus->byUuid($serviceUuids, $conditions);
                $currentHostStateData = $this->Hoststatus->byUuid($hostUuids);
                foreach ($selectedServices as $serviceId => $service) {
                    $servicestatus = $currentServiceStateData[$service['Service']['uuid']];
                    if (!empty($currentHostStateData[$service['Host']['uuid']])) {
                        $hoststatus = $currentHostStateData[$service['Host']['uuid']];
                        if(!isset($currentStateData[$service['Host']['uuid']]['Host'])) {
                            $currentStateData[$service['Host']['uuid']]['Host'] = [
                                'id' => $service['Host']['id'],
                                'name' => $service['Host']['name'],
                                'address' => $service['Host']['address'],
                                'description' => $service['Host']['description'],
                                'Hoststatus' => (empty($hoststatus)) ? [] : [
                                    'current_state' => $hoststatus['Hoststatus']['current_state'],
                                    'perfdata' => $hoststatus['Hoststatus']['perfdata'],
                                    'output' => $hoststatus['Hoststatus']['output'],
                                    'last_state_change' => $hoststatus['Hoststatus']['last_state_change'],
                                ],
                            ];
                        }
                    }

                    if (!empty($servicestatus)) {
                        if (!$serviceStatusExists) {
                            $serviceStatusExists = true;
                        }
                        $currentStateData[$service['Host']['uuid']]['Host']['Services'][$service['Service']['uuid']] = [
                            'Service' => [
                                'name' => (!empty($service['Service']['name']))?$service['Service']['name']:$service['Servicetemplate']['name'],
                                'id' => $service['Service']['id'],
                                'uuid' => $service['Service']['uuid'],
                            ],
                            'Servicestatus' => [
                                'current_state' => $servicestatus['Servicestatus']['current_state'],
                                'perfdata' => $servicestatus['Servicestatus']['perfdata'],
                                'output' => $servicestatus['Servicestatus']['output'],
                                'last_state_change' => $servicestatus['Servicestatus']['last_state_change'],
                            ],
                        ];
                    }
                }
                if (!$serviceStatusExists) {
                    $this->Session->setFlash(__('No service status information within specified filter found'), 'default', ['class' => 'alert auto-hide alert-info']);
                } else {
                    if ($this->request->data('Currentstatereport.report_format') == 'pdf') {
                        $this->Session->write('currentStateData', $currentStateData);
                        $this->redirect([
                            'action' => 'createPdfReport',
                            'ext'    => 'pdf',
                        ]);

                    } else {
                        $this->set(compact(['currentStateData']));
                        $this->render('/Elements/load_current_state_report_data');
                    }
                }
            }
        }
    }

    public function createPdfReport()
    {
        $this->set('currentStateData', $this->Session->read('currentStateData'));
        if ($this->Session->check('currentStateData')) {
            $this->Session->delete('currentStateData');
        }

        $binary_path = '/usr/bin/wkhtmltopdf';
        if (file_exists('/usr/local/bin/wkhtmltopdf')) {
            $binary_path = '/usr/local/bin/wkhtmltopdf';
        }
        $this->pdfConfig = [
            'engine'             => 'CakePdf.WkHtmlToPdf',
            'margin'             => [
                'bottom' => 15,
                'left'   => 0,
                'right'  => 0,
                'top'    => 15,
            ],
            'encoding'           => 'UTF-8',
            'download'           => true,
            'binary'             => $binary_path,
            'orientation'        => 'portrait',
            'filename'           => 'Currentstatereport.pdf',
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }
}
