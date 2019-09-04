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

use App\Model\Table\ContactsTable;
use App\Model\Table\HostescalationsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\TimeperiodsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\TimeperiodsFilter;

/**
 * Class TimeperiodsController
 * @property AppPaginatorComponent $Paginator
 */
class TimeperiodsController extends AppController {
    public $layout = 'Admin.default';

    public $uses = [
        'Timeperiod',
        'Timerange',
        'Calendar'
    ];


    function index() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            //Legacy API Request
            $this->set('all_timeperiods', $TimeperiodsTable->getAllTimeperiodsAsCake2($this->MY_RIGHTS));
            $this->set('_serialize', ['all_timeperiods']);
            return;
        }

        if ($this->isAngularJsRequest()) {
            //AngularJS API Request
            $TimeperiodsFilter = new TimeperiodsFilter($this->request);
            $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $TimeperiodsFilter->getPage());
            $all_timeperiods = $TimeperiodsTable->getTimeperiodsIndex($TimeperiodsFilter, $PaginateOMat);

            foreach ($all_timeperiods as $index => $timeperiod) {
                $allowEdit = $this->hasRootPrivileges;
                if ($this->hasRootPrivileges === false) {
                    $allowEdit = $this->isWritableContainer($timeperiod['Timeperiod']['container_id']);
                }
                $all_timeperiods[$index]['Timeperiod']['allow_edit'] = $allowEdit;
            }


            $this->set('all_timeperiods', $all_timeperiods);
            $toJson = ['all_timeperiods', 'paging'];
            if ($this->isScrollRequest()) {
                $toJson = ['all_timeperiods', 'scroll'];
            }
            $this->set('_serialize', $toJson);
            return;
        }

    }

    public function view($id) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');


        if (!$TimeperiodsTable->exists($id)) {
            throw new NotFoundException(__('Invalid timeperiod'));
        }
        $timeperiod = $TimeperiodsTable->get($id);
        $timeperiod = $timeperiod->toArray();

        if (!$this->allowedByContainerId(Hash::extract($timeperiod, 'container_id'))) {
            $this->render403();
            return;
        }

        $this->set('timeperiod', $timeperiod);
        $this->set('_serialize', ['timeperiod']);
    }

    /**
     * @param null $id
     * @throws Exception
     * @todo refactor me
     */
    public function edit($id = null) {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        if (!$TimeperiodsTable->existsById($id)) {
            throw new NotFoundException('Time period not found');
        }
        $timeperiod = $TimeperiodsTable->get($id, [
            'contain' => 'timeperiodtimeranges'
        ]);
        $timeperiodForChangeLog['Timeperiod'] = $timeperiod->toArray();

        if (!$this->allowedByContainerId($timeperiod->get('container_id'))) {
            $this->render403();
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $timeperiod = $TimeperiodsTable->patchEntity($timeperiod, $this->request->data('Timeperiod'));
            $TimeperiodsTable->checkRules($timeperiod);
            $TimeperiodsTable->save($timeperiod);
            if ($timeperiod->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $timeperiod->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors
                $userId = $this->Auth->user('id');
                $requestData = $this->request->data;

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    $this->params['controller'],
                    $timeperiod->get('id'),
                    OBJECT_TIMEPERIOD,
                    [$requestData['Timeperiod']['container_id']],
                    $userId,
                    $requestData['Timeperiod']['name'],
                    $requestData,
                    $timeperiodForChangeLog
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($timeperiod); // REST API ID serialization
                    return;
                }
            }
        }

        $this->set('timeperiod', $timeperiod);
        $this->set('_serialize', ['timeperiod']);
    }

    /**
     * @throws Exception
     */
    public function add() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $timeperiod = $TimeperiodsTable->newEntity();
            $timeperiod = $TimeperiodsTable->patchEntity($timeperiod, $this->request->data('Timeperiod'));
            $timeperiod->set('uuid', \itnovum\openITCOCKPIT\Core\UUID::v4());
            $TimeperiodsTable->checkRules($timeperiod);
            $TimeperiodsTable->save($timeperiod);
            if ($timeperiod->hasErrors()) {
                $this->response->statusCode(400);
                $this->serializeCake4ErrorMessage($timeperiod);
                return;
            } else {
                //No errors
                $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
                $requestData = $this->request->data;
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    $this->params['controller'],
                    $timeperiod->get('id'),
                    OBJECT_TIMEPERIOD,
                    [ROOT_CONTAINER],
                    $User->getId(),
                    $requestData['Timeperiod']['name'],
                    $requestData
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->serializeCake4Id($timeperiod);
            }
            $this->set('timeperiod', $timeperiod);
            $this->set('_serialize', ['timeperiod']);
        }
    }

    /**
     * @param $timeperiod
     * @return bool
     * @todo refactor me and move me to TimeperiodsTable
     * @deprecated
     */
    protected function __allowDelete($timeperiod) {
        if (is_numeric($timeperiod)) {
            $timeperiodId = $timeperiod;
        } else {
            $timeperiodId = $timeperiod['Timeperiod']['id'];
        }

        //Check contacts
        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        if ($ContactsTable->isTimeperiodUsedByContacts($timeperiodId)) {
            return false;
        }

        //Check service templates
        $this->loadModel('Servicetemplate');
        $servicetemplateCount = $this->Servicetemplate->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'or' => [
                    'check_period_id'  => $timeperiodId,
                    'notify_period_id' => $timeperiodId,
                ],
            ],
        ]);
        if ($servicetemplateCount > 0) {
            return false;
        }

        //Check services
        $this->loadModel('Service');
        $serviceCount = $this->Service->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'or' => [
                    'check_period_id'  => $timeperiodId,
                    'notify_period_id' => $timeperiodId,
                ],
            ],
        ]);
        if ($serviceCount > 0) {
            return false;
        }

        //Check host templates
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        if ($HosttemplatesTable->isTimeperiodUsedByHosttemplate($timeperiodId)) {
            return false;
        }

        //Check hosts
        $this->loadModel('Host');
        $hostCount = $this->Host->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'or' => [
                    'check_period_id'  => $timeperiodId,
                    'notify_period_id' => $timeperiodId,
                ],
            ],
        ]);
        if ($hostCount > 0) {
            return false;
        }

        //Check autoreports
        if (in_array('AutoreportModule', CakePlugin::loaded())) {
            $this->loadModel('AutoreportModule.Autoreport');
            $autoreportCount = $this->Autoreport->find('count', [
                'recursive'  => -1,
                'conditions' => [
                    'timeperiod_id' => $timeperiodId,
                ],
            ]);
            if ($autoreportCount > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');

        if (!$TimeperiodsTable->exists($id)) {
            throw new NotFoundException(__('Timeperiod not found'));
        }

        $timeperiod = $TimeperiodsTable->getTimeperiodById($id);

        if (!$this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
            $this->render403();
            return;
        }

        if (!$this->__allowDelete($timeperiod)) {
            $usedBy = [
                [
                    'baseUrl' => '#',
                    'message' => __('Used by other objects'),
                    'module'  => 'Core'
                ]
            ];

            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting timeperiod'));
            $this->set('usedBy', $usedBy);
            $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
            return;
        }


        $timeperiodEntity = $TimeperiodsTable->get($id);
        if ($TimeperiodsTable->delete($timeperiodEntity)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'delete',
                'timeperiods',
                $id,
                OBJECT_TIMEPERIOD,
                [$timeperiod['Timeperiod']['container_id']],
                $User->getId(),
                $timeperiod['Timeperiod']['name'],
                $timeperiod
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }

            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
        return;
    }

    /**
     * @param int|null $id
     */
    public function copy($id = null) {
        $this->layout = 'blank';

        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');

        if ($this->request->is('get')) {
            $timeperiods = $TimeperiodsTable->getTimeperiodsForCopy(func_get_args());
            $this->set('timeperiods', $timeperiods);
            $this->set('_serialize', ['timeperiods']);
            return;
        }

        $hasErrors = false;

        if ($this->request->is('post')) {
            $Cache = new KeyValueStore();

            $postData = $this->request->data('data');
            $userId = $this->Auth->user('id');

            foreach ($postData as $index => $timeperiodData) {
                if (!isset($timeperiodData['Timeperiod']['id'])) {
                    //Create/clone timeperiod
                    $sourceTimeperiodId = $timeperiodData['Source']['id'];
                    if (!$Cache->has($sourceTimeperiodId)) {
                        $sourceTimeperiod = $TimeperiodsTable->get($sourceTimeperiodId, [
                            'contain' => [
                                'TimeperiodTimeranges'
                            ]
                        ])->toArray();
                        foreach ($sourceTimeperiod['timeperiod_timeranges'] as $i => $timerange) {
                            unset($sourceTimeperiod['timeperiod_timeranges'][$i]['id']);
                            unset($sourceTimeperiod['timeperiod_timeranges'][$i]['timeperiod_id']);
                        }

                        $Cache->set($sourceTimeperiod['id'], $sourceTimeperiod);
                    }

                    $sourceTimeperiod = $Cache->get($sourceTimeperiodId);


                    $newTimeperiodData = [
                        'name'                  => $timeperiodData['Timeperiod']['name'],
                        'description'           => $timeperiodData['Timeperiod']['description'],
                        'container_id'          => $sourceTimeperiod['container_id'],
                        'calendar_id'           => $sourceTimeperiod['calendar_id'],
                        'uuid'                  => \itnovum\openITCOCKPIT\Core\UUID::v4(),
                        'timeperiod_timeranges' => $sourceTimeperiod['timeperiod_timeranges']
                    ];

                    $newTimeperiodEntity = $TimeperiodsTable->newEntity($newTimeperiodData);
                }

                $action = 'copy';
                if (isset($timeperiodData['Timeperiod']['id'])) {
                    //Update existing timeperiod
                    //This happens, if a user copy multiple timeperiods, and one run into an validation error
                    //All timeperiods without validation errors got already saved to the database
                    $newTimeperiodEntity = $TimeperiodsTable->get($timeperiodData['Timeperiod']['id']);
                    $newTimeperiodEntity = $TimeperiodsTable->patchEntity($newTimeperiodEntity, $timeperiodData['Timeperiod']);
                    $newTimeperiodData = $newTimeperiodEntity->toArray();
                    $action = 'edit';
                }
                $TimeperiodsTable->save($newTimeperiodEntity);

                $postData[$index]['Error'] = [];
                if ($newTimeperiodEntity->hasErrors()) {
                    $hasErrors = true;
                    $postData[$index]['Error'] = $newTimeperiodEntity->getErrors();
                } else {
                    //No errors
                    $postData[$index]['Timeperiod']['id'] = $newTimeperiodEntity->get('id');

                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $action,
                        'timeperiods',
                        $postData[$index]['Timeperiod']['id'],
                        OBJECT_TIMEPERIOD,
                        [ROOT_CONTAINER],
                        $userId,
                        $newTimeperiodEntity->get('name'),
                        ['Timeperiod' => $newTimeperiodData]
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }
            }
        }

        if ($hasErrors) {
            $this->response->statusCode(400);
        }
        $this->set('result', $postData);
        $this->set('_serialize', ['result']);
    }

    public function loadTimeperiodsByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $timeperiods = $TimeperiodsTable->getTimeperiodByContainerIdsAsList([
            ROOT_CONTAINER, $containerId
        ]);

        $timeperiods = Api::makeItJavaScriptAble(
            $timeperiods
        );

        $this->set('timeperiods', $timeperiods);
        $this->set('_serialize', ['timeperiods']);
    }
}
