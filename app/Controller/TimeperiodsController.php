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

use App\Model\Table\ContainersTable;
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
        $userId = $this->Auth->user('id');
        if (!$this->Timeperiod->exists($id)) {
            throw new NotFoundException(__('Invalid timeperiod'));
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_TIMEPERIOD, [], $this->hasRootPrivileges);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_TIMEPERIOD, [], $this->hasRootPrivileges);
        }
        $timeperiod = $this->Timeperiod->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
            $this->render403();

            return;
        }

        $_timeperiod = $timeperiod; //for changelog :(
        if (isset($this->request->data['Timerange'])) {
            $timeperiod['Timerange'] = Hash::merge($timeperiod['Timerange'], $this->request->data['Timerange']);
        }
        $date = new DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = $date->format('l');
            $date->modify('+1 day');
        }
        ksort($weekdays);
        if (!$timeperiod) {
            throw new NotFoundException(__('Invalid timeperiod'));
        }

        $day = [];
        $start = [];
        $end = [];
        foreach ($timeperiod['Timerange'] as $key => $row) {
            $day[$key] = $row['day'];
            $start[$key] = $row['start'];
            $end[$key] = $row['end'];
        }
        array_multisort($day, SORT_ASC, $start, SORT_ASC, $end, SORT_ASC, $timeperiod['Timerange']);

        $containerIds = $ContainersTable->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
            ],
            'conditions' => [
                'Calendar.container_id' => $containerIds,
            ],
            'order'      => [
                'Calendar.name' => 'ASC',
            ],
        ];
        $calendars = $this->Calendar->find('list', $query);
        $this->set(compact(['containers', 'weekdays', 'timeperiod', 'calendars']));
        $this->set('_serialize', ['timeperiod']);
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Timeperiod->id = $id;
            $this->loadModel('Timerange');

            $timeranges = $this->Timerange->find('all', [
                'conditions' => [
                    'Timerange.timeperiod_id' => $id,
                ],
            ]);

            /* Alle Zeitscheiben die nicht mehr verwendet werden, müssen gelöscht werden*/
            foreach ($timeranges as $timerange) {
                if (!in_array($timerange['Timerange']['id'], Hash::extract($this->request->data, 'Timerange.{n}.id'))) {
                    $this->Timerange->delete($timerange['Timerange']['id']);
                }
            }
            if ($this->Timeperiod->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Timeperiod->id,
                    OBJECT_TIMEPERIOD,
                    [$this->request->data('Timeperiod.container_id')],
                    $userId,
                    $this->request->data('Timeperiod.name'),
                    $this->request->data,
                    $_timeperiod
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('<a href="/timeperiods/edit/%s">Timeperiod</a> successfully saved', $this->Timeperiod->id));
                $this->redirect(['action' => 'index']);
            } else {
                debug($this->Timeperiod->validationErrors);
                $this->set('timerange_errors', $this->Timeperiod->validationErrors);
                $this->setFlash(__('Timeperiod could not be saved'), false);
            }
        }
    }

    /**
     * @throws Exception
     * @todo refactor me
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
            $timeperiod->set('uuid', UUID::v4());
            $TimeperiodsTable->checkRules($timeperiod);
            $TimeperiodsTable->save($timeperiod);
            if ($timeperiod->hasErrors()) {
                $this->response->statusCode(400);
                $this->serializeCake4ErrorMessage($timeperiod);
                return;
            }
            $userId = $this->Auth->user('id');
            $requestData = $this->request->data;
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'add',
                $this->params['controller'],
                $timeperiod->get('id'),
                OBJECT_TIMEPERIOD,
                [ROOT_CONTAINER],
                $userId,
                $requestData['Timeperiod']['name'],
                $requestData
            );
            if ($changelog_data) {
                CakeLog::write('log', serialize($changelog_data));
            }
            $this->serializeCake4Id($timeperiod);
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
        $this->loadModel('Contact');
        $contactCount = $this->Contact->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'or' => [
                    'host_timeperiod_id'    => $timeperiodId,
                    'service_timeperiod_id' => $timeperiodId,
                ],
            ],
        ]);
        if ($contactCount > 0) {
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
        $this->loadModel('Hosttemplate');
        $hosttemplateCount = $this->Hosttemplate->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'or' => [
                    'check_period_id'  => $timeperiodId,
                    'notify_period_id' => $timeperiodId,
                ],
            ],
        ]);
        if ($hosttemplateCount > 0) {
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

        //Check host escalations
        $this->loadModel('Hostescalation');
        $hostescalationCount = $this->Hostescalation->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'timeperiod_id' => $timeperiodId,
            ],
        ]);
        if ($hostescalationCount > 0) {
            return false;
        }

        //Check service escalations
        $this->loadModel('Serviceescalation');
        $serviceescalationCount = $this->Serviceescalation->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'timeperiod_id' => $timeperiodId,
            ],
        ]);
        if ($serviceescalationCount > 0) {
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
                        'uuid'                  => UUID::v4(),
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
        $timeperiods = $TimeperiodsTable->getCommandByContainerIdsAsList([
            ROOT_CONTAINER, $containerId
        ]);

        $timeperiods = Api::makeItJavaScriptAble(
            $timeperiods
        );

        $this->set('timeperiods', $timeperiods);
        $this->set('_serialize', ['timeperiods']);
    }
}
