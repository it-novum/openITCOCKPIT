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

class TimeperiodsController extends AppController
{
    public $layout = 'Admin.default';
    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
    ];
    public $helpers = ['ListFilter.ListFilter'];

    public $uses = [
        'Timeperiod',
        'Timerange',
        'Calendar'
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Timeperiod.name' => ['label' => 'Name', 'searchType' => 'wildcard'],
                'Timeperiod.description' => ['label' => 'Description', 'searchType' => 'wildcard'],
            ],
        ],
    ];


    function index()
    {
        $options = [
            'recursive' => -1,
            'order' => [
                'Timeperiod.name' => 'asc',
            ],
            'conditions' => [
                'Timeperiod.container_id' => $this->MY_RIGHTS,
            ],
        ];

        if ($this->isApiRequest()) {
            $this->set('all_timeperiods', $this->Timeperiod->find('all', $options));
        } else {
            $this->Paginator->settings = Hash::merge($this->Paginator->settings, $options);
            $this->set('all_timeperiods', $this->Paginator->paginate());
        }
        //Aufruf für json oder xml view: /nagios_module/hosts.json oder /nagios_module/hosts.xml
        $this->set('_serialize', ['all_timeperiods']);
    }

    public function view($id)
    {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }
        if (!$this->Timeperiod->exists($id)) {
            throw new NotFoundException(__('Invalid timeperiod'));
        }
        $timeperiod = $this->Timeperiod->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
            $this->render403();

            return;
        }
        $this->set('timeperiod', $timeperiod);
        $this->set('_serialize', ['timeperiod']);
    }

    public function edit($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->Timeperiod->exists($id)) {
            throw new NotFoundException(__('Invalid timeperiod'));
        }

        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_TIMEPERIOD, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_TIMEPERIOD, [], $this->hasRootPrivileges);
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

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
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
                $this->set('timerange_errors', $this->Timeperiod->validationErrors);
                $this->setFlash(__('Timeperiod could not be saved'), false);
            }
        }
    }

    public function add()
    {
        $userId = $this->Auth->user('id');
        if ($this->hasRootPrivileges === true) {
            $containers = $this->Tree->easyPath($this->MY_RIGHTS, OBJECT_TIMEPERIOD, [], $this->hasRootPrivileges);
        } else {
            $containers = $this->Tree->easyPath($this->getWriteContainers(), OBJECT_TIMEPERIOD, [], $this->hasRootPrivileges);
        }
        $timeranges = (isset($this->request->data['Timerange'])) ? $this->request->data['Timerange'] : [];
        $date = new DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = $date->format('l');
            $date->modify('+1 day');
        }
        ksort($weekdays);
        $day = [];
        $start = [];
        $end = [];
        foreach ($timeranges as $key => $row) {
            $day[$key] = $row['day'];
            $start[$key] = $row['start'];
            $end[$key] = $row['end'];
        }
        array_multisort($day, SORT_ASC, $start, SORT_ASC, $end, SORT_ASC, $timeranges);
        $this->set(compact([
            'containers',
            'weekdays',
            'timeranges',
        ]));

        $this->set('timeranges', $timeranges);
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Timeperiod->set($this->request->data);
            $this->request->data['Timeperiod']['uuid'] = UUID::v4();
            if ($this->Timeperiod->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Timeperiod->id,
                    OBJECT_TIMEPERIOD,
                    [$this->request->data('Timeperiod.container_id')],
                    $userId,
                    $this->request->data['Timeperiod']['name'],
                    $this->request->data
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }


                if ($this->request->ext == 'json') {
                    $this->serializeId();
                } else {
                    $this->setFlash(__('<a href="/timeperiods/edit/%s">Timeperiod</a> successfully saved', $this->Timeperiod->id));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();
                } else {
                    $this->set('timerange_errors', $this->Timeperiod->validationErrors);
                    $this->setFlash(__('Timeperiod could not be saved'), false);
                }
            }
        }

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
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
        $this->set('calendars', $calendars);
    }

    protected function __allowDelete($timeperiod)
    {
        if (is_numeric($timeperiod)) {
            $timeperiodId = $timeperiod;
        } else {
            $timeperiodId = $timeperiod['Timeperiod']['id'];
        }

        //Check contacts
        $this->loadModel('Contact');
        $contactCount = $this->Contact->find('count', [
            'recursive' => -1,
            'conditions' => [
                'or' => [
                    'host_timeperiod_id' => $timeperiodId,
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
            'recursive' => -1,
            'conditions' => [
                'or' => [
                    'check_period_id' => $timeperiodId,
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
            'recursive' => -1,
            'conditions' => [
                'or' => [
                    'check_period_id' => $timeperiodId,
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
            'recursive' => -1,
            'conditions' => [
                'or' => [
                    'check_period_id' => $timeperiodId,
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
            'recursive' => -1,
            'conditions' => [
                'or' => [
                    'check_period_id' => $timeperiodId,
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
            'recursive' => -1,
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
            'recursive' => -1,
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
                'recursive' => -1,
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

    public function delete($id = null)
    {
        $userId = $this->Auth->user('id');
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Timeperiod->exists($id)) {
            throw new NotFoundException(__('invalid_timeperiod'));
        }

        $timeperiod = $this->Timeperiod->findById($id);

        if (!$this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
            $this->render403();

            return;
        }

        if ($this->__allowDelete($timeperiod)) {
            if ($this->Timeperiod->delete($id)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_TIMEPERIOD,
                    [$timeperiod['Timeperiod']['container_id']],
                    $userId,
                    $timeperiod['Timeperiod']['name'],
                    $timeperiod
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('Timeperiod deleted'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not delete timeperiod'), false);
                $this->redirect(['action' => 'index']);
            }
        } else {
            $timeperiodsCanotDelete = [$timeperiod['Timeperiod']['name']];
            $this->set(compact(['timeperiodsCanotDelete']));
            $this->render('mass_delete');
        }
    }

    public function mass_delete($id = null)
    {
        $userId = $this->Auth->user('id');
        //PUT/POST request (form submit)
        if ($this->request->is('post') || $this->request->is('put')) {
            foreach ($this->request->data('Timeperiod.delete') as $timeperiodId) {
                if ($this->Timeperiod->exists($timeperiodId)) {
                    $timeperiod = $this->Timeperiod->findById($timeperiodId);
                    if ($this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
                        if ($this->Timeperiod->delete($timeperiodId)) {
                            $changelog_data = $this->Changelog->parseDataForChangelog(
                                $this->params['action'],
                                $this->params['controller'],
                                $timeperiodId,
                                OBJECT_TIMEPERIOD,
                                [$timeperiod['Timeperiod']['container_id']],
                                $userId,
                                $timeperiod['Timeperiod']['name'],
                                $timeperiod
                            );
                            if ($changelog_data) {
                                CakeLog::write('log', serialize($changelog_data));
                            }
                        }
                    }
                }
            }
            $this->setFlash(__('Timeperiods deleted'));
            $this->redirect(['action' => 'index']);
        }

        //GET request
        $timeperiodsToDelete = [];
        $timeperiodsCanotDelete = [];
        foreach (func_get_args() as $timeperiodId) {
            if ($this->Timeperiod->exists($timeperiodId)) {
                $timeperiod = $this->Timeperiod->findById($timeperiodId);
                if ($this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
                    if ($this->__allowDelete($timeperiod)) {
                        $timeperiodsToDelete[] = $timeperiod;
                    } else {
                        $timeperiodsCanotDelete[] = $timeperiod['Timeperiod']['name'];
                    }
                }
            }
        }
        $count = sizeof($timeperiodsToDelete) + sizeof($timeperiodsCanotDelete);
        $this->set(compact(['timeperiodsToDelete', 'timeperiodsCanotDelete', 'count']));
    }

    function browser($id = null)
    {
    }

    function controller()
    {
        return 'TimeperiodsController';
    }

    public function copy($id = null)
    {
        $userId = $this->Auth->user('id');
        $timeperiods = $this->Timeperiod->find('all', [
            'contain' => [
                'Timerange' => [
                    'fields' => [
                        'Timerange.day',
                        'Timerange.start',
                        'Timerange.end'
                    ]
                ]
            ],
            'conditions' => [
                'Timeperiod.id' => func_get_args(),
            ],
            'fields' => [
                'Timeperiod.name',
                'Timeperiod.container_id',
                'Timeperiod.description',
            ]
        ]);


        $timeperiods = Hash::combine($timeperiods, '{n}.Timeperiod.id', '{n}');
        $timeperiods = Hash::remove($timeperiods, '{n}.Timerange.{n}.timeperiod_id'); //clean up time ranges
        if ($this->request->is('post') || $this->request->is('put')) {
            $datasource = $this->Timeperiod->getDataSource();
            try {
                $datasource->begin();
                foreach ($this->request->data['Timeperiod'] as $sourcePeriodId => $timeperiodData) {
                    $timeRanges = [];
                    if(!empty($timeperiods[$sourcePeriodId]['Timerange'])){
                        $timeRanges = $timeperiods[$sourcePeriodId]['Timerange'];
                    }
                    $newTimeperiodData = [
                        'Timeperiod' => [
                            'uuid' => UUID::v4(),
                            'name' => $timeperiodData['name'],
                            'container_id' => $timeperiodData['container_id'],
                            'description' => $timeperiodData['description'],
                        ],
                        'Timerange' => $timeRanges,
                    ];
                    $this->Timeperiod->create();
                    if (!$this->Timeperiod->saveAll($newTimeperiodData)) {
                        throw new Exception('Some of the Timeperiods could not be copied');
                    }
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $this->Timeperiod->id,
                        OBJECT_TIMEPERIOD,
                        [$timeperiodData['container_id']],
                        $userId,
                        $timeperiodData['name'],
                        $newTimeperiodData
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }
                }

                $datasource->commit();
                $this->setFlash(__('Timeperiods are successfully copied'));
                $this->redirect(['action' => 'index']);

            } catch (Exception $e) {
                $datasource->rollback();
                $this->setFlash(__($e->getMessage()), false);
                $this->redirect(['action' => 'index']);
            }

        }

        $this->set(compact('timeperiods'));
        $this->set('back_url', $this->referer());
    }

    public function loadTimeperiodsByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerId = $this->request->query('containerId');

        $timeperiods = $this->Timeperiod->find('list', [
            'conditions' => [
                'Timeperiod.container_id' => [
                    ROOT_CONTAINER, $containerId
                ]
            ]
        ]);

        $timeperiods = $this->Timeperiod->makeItJavaScriptAble(
            $timeperiods
        );

        $this->set(compact(['timeperiods']));
        $this->set('_serialize', ['timeperiods']);
    }
}
