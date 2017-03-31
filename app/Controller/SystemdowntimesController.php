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
 * @property Systemdowntime $Systemdowntime
 * @property Host           $Host
 * @property Service        $Service
 * @property Hostgroup      $Hostgroup
 */
class SystemdowntimesController extends AppController
{
    public $uses = [
        'Systemdowntime',
        'Host',
        'Service',
        'Hostgroup',
    ];
    public $components = [
        'Paginator',
        'ListFilter.ListFilter',
        'RequestHandler',
        'CustomValidationErrors',
        'GearmanClient',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
        'Status',
        'Monitoring',
        'CustomValidationErrors',
        'Uuid',
    ];
    public $layout = 'Admin.default';

    public function index()
    {
        $paginatorLimit = $this->Paginator->settings['limit'];
        $requestSettings = $this->Systemdowntime->listSettings($this->request, $paginatorLimit);

        if (isset($this->Paginator->settings['conditions'])) {
            $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        } else {
            $this->Paginator->settings['conditions'] = $requestSettings['conditions'];
        }

        $this->Paginator->settings['limit'] = $requestSettings['paginator']['limit'];
        $this->Paginator->settings['conditions'] = Hash::merge($this->Paginator->settings['conditions'], $requestSettings['conditions']);
        $this->Paginator->settings = Hash::merge($this->Paginator->settings, $requestSettings['default']);

        $all_systemdowntimes = $this->Paginator->paginate();

        $this->set('DowntimeListsettings', $requestSettings['Listsettings']);
        $this->set('all_systemdowntimes', $all_systemdowntimes);
        $this->set('paginatorLimit', $paginatorLimit);

        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }
    }

    public function addHostdowntime()
    {
        $selected = $this->request->data('Systemdowntime.object_id');

        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);

        $customFildsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'is_recurring',
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $hosts = $this->Host->hostsByContainerId($containerIds, 'list');

        $this->set(compact(['hosts', 'selected']));
        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }
            $this->request->data = $this->_rewritePostData();
            //Try validate the data:
            foreach ($this->request->data as $request) {
                if($this->request->data('Systemdowntime.is_recurring')){
                    $this->Systemdowntime->validate = Hash::merge(
                        $this->Systemdowntime->validate,
                        [
                            'from_date' => [
                                'notBlank' => [
                                    'required' => false,
                                    'allowEmpty' => true,
                                 ],
                            ],
                            'to_date' => [
                                'notBlank' => [
                                    'required' => false,
                                    'allowEmpty' => true,
                                ]
                            ]
                        ]
                    );
                }
                $this->Systemdowntime->set($request);
                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handel saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */

                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->create();
                        $this->Systemdowntime->save($request);
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'].' '.$request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'].' '.$request['Systemdowntime']['to_time']);
                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'host') {
                            $host = $this->Host->find('first', [
                                'recursive' => -1,
                                'fields' => [
                                    'Host.uuid'
                                ],
                                'conditions' => [
                                    'Host.id' => $request['Systemdowntime']['object_id']
                                ]
                            ]);
                            $payload = [
                                'hostUuid'     => $host['Host']['uuid'],
                                'downtimetype' => $request['Systemdowntime']['downtimetype_id'],
                                'start'        => $start,
                                'end'          => $end,
                                'comment'      => $request['Systemdowntime']['comment'],
                                'author'       => $this->Auth->user('full_name'),
                            ];
                            $this->GearmanClient->sendBackground('createHostDowntime', $payload);
                        }
                    }

                } else {
                    $this->setFlash(__('Downtime could not be saved'), false);
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();

                    return;
                }
            }
            $this->setFlash(__('Downtime successfully saved'));
            $this->redirect(['controller' => 'downtimes', 'action' => 'index']);
        }
    }

    public function addHostgroupdowntime()
    {

        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);

        $selected = $this->getNamedParameter('hostgroup_id', []);

        $customFildsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'is_recurring',
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $hostgroups = $this->Hostgroup->hostgroupsByContainerId($containerIds, 'list');

        $this->set(compact(['hostgroups', 'selected']));
        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $this->request->data = $this->_rewritePostData();

            //Try validate the data:
            foreach ($this->request->data as $request) {
                $this->Systemdowntime->set($request);
                //debug($request);
                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handel saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */
                    $this->setFlash(__('Downtime successfully saved'));
                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->save($request);
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'].' '.$request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'].' '.$request['Systemdowntime']['to_time']);
                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'hostgroup') {
                            $hostgroup = $this->Hostgroup->find('first', [
                                'recursive'  => -1,
                                'conditions' => [
                                    'Hostgroup.container_id' => $request['Systemdowntime']['object_id'],
                                ],
                                'fields'     => [
                                    'Hostgroup.uuid',
                                ],
                            ]);
                            $payload = [
                                'hostgroupUuid' => $hostgroup['Hostgroup']['uuid'],
                                'downtimetype'  => $request['Systemdowntime']['downtimetype_id'],
                                'start'         => $start,
                                'end'           => $end,
                                'comment'       => $request['Systemdowntime']['comment'],
                                'author'        => $this->Auth->user('full_name'),
                            ];

                            $this->GearmanClient->sendBackground('createHostgroupDowntime', $payload);
                        }
                    }

                } else {
                    $this->setFlash(__('Downtime could not be saved'), false);
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();

                    return;
                }
            }
            $this->redirect(['controller' => 'downtimes', 'action' => 'index']);
        }
    }

    public function addServicedowntime()
    {
        $this->Frontend->setJson('dateformat', MY_DATEFORMAT);
        $selected = $this->getNamedParameter('service_id', []); // FIXME Isn't used (yet?). Remove?

        $customFildsToRefill = [
            'Systemdowntime' => [
                'from_date',
                'from_time',
                'to_date',
                'to_time',
                'is_recurring',
            ],
        ];
        $this->CustomValidationErrors->checkForRefill($customFildsToRefill);


        $services = $this->Service->servicesByHostContainerIds($this->MY_RIGHTS);
        $services = Hash::combine($services, '{n}.Service.id', ['%s/%s', '{n}.Host.name', '{n}.{n}.ServiceDescription'], '{n}.Host.name');

        $this->set(compact(['services', 'selected']));
        $this->set('back_url', $this->referer());

        if ($this->request->is('post') || $this->request->is('put')) {

            if (isset($this->request->data['Systemdowntime']['weekdays']) && is_array($this->request->data['Systemdowntime']['weekdays'])) {
                $this->request->data['Systemdowntime']['weekdays'] = implode(',', $this->request->data['Systemdowntime']['weekdays']);
            }

            $this->request->data = $this->_rewritePostData();

            //Try validate the data:
            foreach ($this->request->data as $request) {
                $this->Systemdowntime->set($request);
                if ($this->Systemdowntime->validates()) {
                    /* The data is valide and we can save it.
                     * We need to use the foreach, becasue validates() cant handel saveAll() data :(
                     *
                     * How ever, at this point we passed the validation, so the data is valid.
                     * Now we need to check, if this is just a downtime, or an recurring downtime, because
                     * these guys we want to save in our systemdowntimestable
                     * Normal downtimes, will be sent to sudo_servers unix socket.
                     */
                    $this->setFlash(__('Downtime successfully saved'));

                    if ($request['Systemdowntime']['is_recurring'] == 1) {
                        $this->Systemdowntime->save($request);
                    } else {
                        $start = strtotime($request['Systemdowntime']['from_date'].' '.$request['Systemdowntime']['from_time']);
                        $end = strtotime($request['Systemdowntime']['to_date'].' '.$request['Systemdowntime']['to_time']);
                        //Just a normal nagios downtime
                        if ($request['Systemdowntime']['downtimetype'] == 'service') {
                            $service = $this->Service->findById($request['Systemdowntime']['object_id']);
                            $payload = [
                                'hostUuid'    => $service['Host']['uuid'],
                                'serviceUuid' => $service['Service']['uuid'],
                                'start'       => $start,
                                'end'         => $end,
                                'comment'     => $request['Systemdowntime']['comment'],
                                'author'      => $this->Auth->user('full_name'),
                            ];
                            //debug($payload);
                            $this->GearmanClient->sendBackground('createServiceDowntime', $payload);
                        }
                    }

                } else {
                    $this->setFlash(__('Downtime could not be saved'), false);
                    $this->CustomValidationErrors->loadModel($this->Systemdowntime);
                    $this->CustomValidationErrors->customFields(['from_date', 'from_time', 'to_date', 'to_time', 'downtimetype']);
                    $this->CustomValidationErrors->fetchErrors();

                    return;
                }
            }
            $this->redirect(['controller' => 'downtimes', 'action' => 'service']);
        }
    }

    public function delete($id = null, $cascade = true)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $this->Systemdowntime->id = $id;
        if (!$this->Systemdowntime->exists()) {
            throw new NotFoundException(__('Invalide downtime'));
        }

        $systemdowntime = $this->Systemdowntime->findById($id);

        if ($this->Systemdowntime->delete()) {
            $this->setFlash(__('Recurring downtime deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete recurring downtime'));
        $this->redirect(['action' => 'index']);

    }

    private function _rewritePostData()
    {
        /*

        why we need this function? The problem is, may be a user want to save the downtime for more that one hast. the array we get from $this->reuqest->data looks like this:
            array(
                'Systemdowntime' => array(
                    'downtimetype' => 'host',
                    'object_id' => array(
                        (int) 0 => '1',
                        (int) 1 => '2'
                    ),
                    'downtimetype_id' => '0',
                    'comment' => 'In maintenance',
                    'is_recurring' => '1',
                    'weekdays' => '1',
                    'recurring_days_month' => '1',
                    'from_date' => '11.09.2014',
                    'from_time' => '99:99',
                    'to_date' => '14.09.2014',
                    'to_time' => '06:09'
                )
            )

        the big problem is the object_id, rthis thorws us an "Array to string conversion". So we need to rewirte the post array fo some like this:

        array(
            (int) 0 => array(
                'Systemdowntime' => array(
                    'downtimetype' => 'host',
                    'object_id' => '2',
                    'downtimetype_id' => '0',
                    'comment' => 'In maintenance',
                    'is_recurring' => '1',
                    'weekdays' => '',
                    'recurring_days_month' => 'asdadasd',
                    'from_date' => '11.09.2014',
                    'from_time' => '06:09',
                    'to_date' => '14.09.2014',
                    'to_time' => '06:09'
                )
            ),
            (int) 1 => array(
                'Systemdowntime' => array(
                    'downtimetype' => 'host',
                    'object_id' => '3',
                    'downtimetype_id' => '0',
                    'comment' => 'In maintenance',
                    'is_recurring' => '1',
                    'weekdays' => '',
                    'recurring_days_month' => 'asdadasd',
                    'from_date' => '11.09.2014',
                    'from_time' => '06:09',
                    'to_date' => '14.09.2014',
                    'to_time' => '06:09'
                )
            )
        )

        */

        $return = [];
        if (is_array($this->request->data['Systemdowntime']['object_id'])) {
            foreach ($this->request->data['Systemdowntime']['object_id'] as $object_id) {
                $tmp['Systemdowntime'] = $this->request->data['Systemdowntime'];
                $tmp['Systemdowntime']['object_id'] = $object_id;
                $tmp['Systemdowntime']['author'] = $this->Auth->user('full_name');
                $return[] = $tmp;
            }
        } else {
            $this->setFlash(__('Please selete a host'), false);
        }

        return $return;

    }
}
