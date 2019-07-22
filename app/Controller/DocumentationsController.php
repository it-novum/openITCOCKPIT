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
 * Class DocumentationsController
 * @property Documentation $Documentation
 */
class DocumentationsController extends AppController {

    public $layout = 'blank';

    public $uses = [
        'Documentation',
        'Host',
        'Service'
    ];

    /**
     * @param null $uuid
     * @param string $type
     * @throws Exception
     * @deprecated
     */
    public function view($uuid = null, $type = 'host') {
        if (empty($type)) {
            throw new InvalidArgumentException();
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Documentation->save($this->request->data)) {
                if ($this->request->ext == 'json') {
                    $this->serializeId(); // REST API ID serialization
                    return;
                }
            }
            $this->serializeErrorMessage();
            return;

        }

        if (!$this->isAngularJsRequest() && $uuid === null) {
            //Host for .html requests
            return;
        }

        $post = $this->Documentation->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Documentation.uuid' => $uuid
            ]
        ]);

        if (!$this->isAngularJsRequest() && $uuid === null) {
            //Host for .html requests
            return;
        }

        $allowEdit = false;
        $host = [];
        if ($type == 'host') {

            $host = $this->Host->find('first', [
                'fields'     => [
                    'Host.id',
                    'Host.uuid',
                    'Host.name',
                    'Host.address',
                    'Host.container_id',
                    'Host.host_url',
                    'Host.host_type',
                ],
                'conditions' => [
                    'Host.uuid' => $uuid,
                ],
                'contain'    => [
                    'Container',
                ],
            ]);

            if (empty($host)) {
                throw new NotFoundException(__('invalid host'));
            }

            $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
            $containerIdsToCheck[] = $host['Host']['container_id'];

            //Check if user is permitted to see this object
            if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
                $this->render403();

                return;
            }

            //Check if user is permitted to edit this object
            $allowEdit = false;
            if ($this->allowedByContainerId($containerIdsToCheck)) {
                $allowEdit = true;
            }

        }

        $service = [];
        if ($type == 'service') {

            $service = $this->Service->find('first', [
                'recursive'  => -1,
                'contain'    => [
                    'Host'            => [
                        'Container',
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                        ],
                    ],
                ],
                'conditions' => [
                    'Service.uuid' => $uuid,
                ],

            ]);

            if (empty($service)) {
                throw new NotFoundException(__('invalid service'));
            }

            $host = $this->Host->findById($service['Service']['host_id']);
            $containerIdsToCheck = Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
            $containerIdsToCheck[] = $host['Host']['container_id'];
            if (!$this->allowedByContainerId($containerIdsToCheck, false)) {
                $this->render403();

                return;
            }

            $allowEdit = false;
            if ($this->allowedByContainerId($containerIdsToCheck)) {
                $allowEdit = true;
            }

        }

        if (!empty($post) && !empty($post['Documentation']['modified'])) {
            $post['Documentation']['modified_formatted'] = CakeTime::format($post['Documentation']['modified'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'));
        }

        $docuExists = !empty($post);

        $this->set('post', $post);
        $this->set('docuExists', $docuExists);
        $this->set('host', $host);
        $this->set('service', $service);
        $this->set('allowEdit', $allowEdit);
        $this->set('_serialize', ['post', 'docuExists', 'allowEdit', 'host', 'service']);
    }

    /**
     * @throws Exception
     */
    public function wiki() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $documentations = [
            'additional_help' => [
                'name'      => ('Additional Help'),
                'directory' => 'additional_help',
                'children'  => [
                    'mysql_performance'          => [
                        'name'        => __('MySQL performance'),
                        'description' => 'A few tips to optimize your MySQL performance.',
                        'file'        => 'mysql_performance',
                        'icon'        => 'fa fa-database',
                    ],
                    'browser_push_notifications' => [
                        'name'        => __('Browser Push Notifications'),
                        'description' => 'How to setup openITCOCKPIT browser push notifications',
                        'file'        => 'browser_push_notifications',
                        'icon'        => 'fa fa-bell-o',
                    ],
                    'markdown'                   => [
                        'name'        => __('Markdown'),
                        'description' => 'A cheatsheet to help writing markdown formatted texts. ',
                        'file'        => 'markdown',
                        'icon'        => 'fa fa-pencil',
                    ],
                ],
            ],
        ];

        if ($this->request->is('get')) {
            $this->set('documentations', $documentations);
            $this->set('_serialize', ['documentations']);
            return;
        }

        if ($this->request->is('post')) {

            $category = $this->request->data('category');
            $documentation = $this->request->data('documentation');

            if (!isset($documentations[$category]['children'][$documentation])) {
                throw new NotFoundException();
            }

            $file = OLD_APP . 'docs' . DS . 'en' . DS . $documentations[$category]['directory'] . DS . $documentations[$category]['children'][$documentation]['file'] . '.md';
            if (!file_exists($file)) {
                throw new NotFoundException('Markdown file not found!');
            }

            $parsedown = new ParsedownExtra();
            $html = $parsedown->text(file_get_contents($file));

            $this->set('documentation', $documentations[$category]['children'][$documentation]);
            $this->set('html', $html);
            $this->set('_serialize', ['documentation', 'html']);
            return;
        }
    }
}
