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
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;


/**
 * @property Currentstatereport $Currentstatereport
 * @property Host $Host
 * @property Service $Service
 * @property Hoststatus $Hoststatus
 */
class CurrentstatereportsController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Currentstatereport',
        'Host',
        'Service',
    ];

    public function index() {
        $this->layout = 'angularjs';
        $userContainerId = $this->Auth->user('container_id');
        $currentStateData = [];
        $serviceStatusExists = false;
        //ContainerID => 1 for ROOT Container

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS, $this->hasRootPrivileges);
        $services = Hash::combine($this->Service->servicesByHostContainerIds($containerIds),
            '{n}.Service.id', '{n}'
        );
        $this->set(compact(['services', 'userContainerId']));


        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Currentstatereport->set($this->request->data);
            if ($this->Currentstatereport->validates()) {
                foreach ($this->request->data('Currentstatereport.Service') as $serviceId) {
                    if (empty($services[$serviceId]['Service']['uuid'])) continue;
                    $ServicestatusFields = new ServicestatusFields($this->DbBackend);
                    $ServicestatusFields->wildcard();
                    $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
                    $ServicestatusConditions->currentState($this->request->data('Currentstatereport.current_state'));
                    $servicestatus = $this->Servicestatus->byUuid(
                        $services[$serviceId]['Service']['uuid'],
                        $ServicestatusFields,
                        $ServicestatusConditions
                    );
                    if (!isset($currentStateData[$services[$serviceId]['Host']['uuid']]['Host'])) {
                        $HoststatusFields = new HoststatusFields($this->DbBackend);
                        $HoststatusFields
                            ->currentState()
                            ->perfdata()
                            ->output()
                            ->lastStateChange();
                        $hoststatus = $this->Hoststatus->byUuid($services[$serviceId]['Host']['uuid'], $HoststatusFields);
                        $currentStateData[$services[$serviceId]['Host']['uuid']]['Host'] = [
                            'id'          => $services[$serviceId]['Host']['id'],
                            'name'        => $services[$serviceId]['Host']['name'],
                            'address'     => $services[$serviceId]['Host']['address'],
                            'description' => $services[$serviceId]['Host']['description'],
                            'Hoststatus'  => (empty($hoststatus)) ? [] : [
                                'current_state'     => $hoststatus['Hoststatus']['current_state'],
                                'perfdata'          => $hoststatus['Hoststatus']['perfdata'],
                                'output'            => $hoststatus['Hoststatus']['output'],
                                'last_state_change' => $hoststatus['Hoststatus']['last_state_change'],
                            ],
                        ];
                    }
                    if (!empty($servicestatus)) {
                        if (!$serviceStatusExists) {
                            $serviceStatusExists = true;
                        }
                        $currentStateData[$services[$serviceId]['Host']['uuid']]['Host']['Services'][$services[$serviceId]['Service']['uuid']] = [
                            'Service'       => [
                                'name' => $services[$serviceId][0]['ServiceDescription'],
                                'id'   => $services[$serviceId]['Service']['id'],
                                'uuid' => $services[$serviceId]['Service']['uuid'],
                            ],
                            'Servicestatus' => [
                                'current_state'     => $servicestatus['Servicestatus']['current_state'],
                                'perfdata'          => $servicestatus['Servicestatus']['perfdata'],
                                'output'            => $servicestatus['Servicestatus']['output'],
                                'last_state_change' => $servicestatus['Servicestatus']['last_state_change'],
                            ],
                        ];
                    }
                    /*
                    else{
                        $currentStateData[$services[$serviceId]['Host']['uuid']]['Host']['ServicesNotMonitored'][$services[$serviceId]['Service']['uuid']] =  [
                                'Service' => [
                                    'name' => $services[$serviceId][0]['ServiceDescription'],
                                    'id' => $services[$serviceId]['Service']['id']
                            ],
                            'Host' => [
                                'name' => $services[$serviceId]['Host']['name'],
                                'id' => $services[$serviceId]['Host']['id'],
                            ]
                        ];
                    }
                    */
                }

                if (!$serviceStatusExists) {
                    $this->set('response', [
                        'status'     => 200,
                        'statusText' => 'Ok',
                        'message'    => __('No service status information within specified filter found')
                    ]);
                    $this->set('_serialize', ['response']);
                    return;
                }

                $this->Session->write('currentStateData', $currentStateData);


                $this->set('response', [
                    'status'     => 201,
                    'statusText' => 'Created'
                ]);
                $this->set('_serialize', ['response']);
                $this->response->statusCode(201);

                return;

                /*
                //Plain PHP workaround
                $this->redirect([
                    'action' => 'createPdfReport',
                    'ext'    => 'pdf',
                ]);
                */


            } else {
                $this->serializeErrorMessage();
            }
        }
    }

    public function createHtmlReport() {
        $this->set('currentStateData', $this->Session->read('currentStateData'));
        if ($this->Session->check('currentStateData')) {
            $this->Session->delete('currentStateData');
        }
        $this->render('/Elements/load_current_state_report_data');

    }

    public function createPdfReport() {
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
