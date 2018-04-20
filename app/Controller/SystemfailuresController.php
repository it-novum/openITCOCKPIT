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

class SystemfailuresController extends AppController
{
    public $layout = 'Admin.default';

    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
        'AdditionalLinks',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'Status',
        'Monitoring',
        'CustomValidationErrors',
        'CustomVariables',
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Systemfailure.comment' => ['label' => 'Comment', 'searchType' => 'wildcard'],
            ],
        ],
    ];

    public function index()
    {
        $all_systemfailures = $this->Paginator->paginate();

        $this->set(compact(['all_systemfailures']));
    }

    public function add()
    {
        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);

        $customFildsToRefill = [
            'Systemfailure' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
            ],
        ];

        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $this->set('back_url', $this->referer());
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Systemfailure']['user_id'] = $this->Auth->user('id');
            $this->Systemfailure->set($this->request->data);
            if ($this->Systemfailure->validates()) {
                // Data is valide and ready to save
                // Merging from_date and from_time to start_time
                $this->request->data['Systemfailure']['start_time'] = date('Y-m-d H:i:s', strtotime(trim($this->request->data['Systemfailure']['from_date']).' '.trim($this->request->data['Systemfailure']['from_time'])));
                // Merging to_date and to_time to end_time
                $this->request->data['Systemfailure']['end_time'] = date('Y-m-d H:i:s', strtotime(trim($this->request->data['Systemfailure']['to_date']).' '.trim($this->request->data['Systemfailure']['to_time'])));
                if ($this->Systemfailure->save($this->request->data)) {
                    $this->setFlash(__('Systemfailure successfully saved'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->setFlash(__('Systemfailure could not be saved'), false);
            $this->CustomValidationErrors->loadModel($this->Systemfailure);
            $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time']);
            $this->CustomValidationErrors->fetchErrors();
        }
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Systemfailure->id = $id;

        if (!$this->Systemfailure->exists($id)) {
            throw new NotFoundException(__('Systemfailure not found'));
        }


        if ($this->Systemfailure->delete()) {
            $this->setFlash(__('Systemfailure deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete systemfailure'));
        $this->redirect(['action' => 'index']);

    }
}