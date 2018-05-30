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

App::uses('CalendarHolidays', 'Vendor/Date');


/**
 * @property Calendar                $Calendar
 * @property Tenant                  $Tenant
 * @property CalendarHoliday         $CalendarHoliday
 * @property RequestHandlerComponent $RequestHandler
 * @property PaginatorComponent      $Paginator
 */
class CalendarsController extends AppController
{
    public $uses = [
        'Calendar',
        'Tenant',
        'CalendarHoliday',
        'Timeperiod'
    ];
    public $layout = 'Admin.default';
    public $components = [
        'RequestHandler',
    ];

    /**
     * Lists the existing configurations to load and edit them.
     */
    public function index()
    {
        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $query = [
            'recursive'  => -1,
            'contain'    => [
                'Container',
            ],
            'fields'     => [
                'Calendar.id',
                'Calendar.name',
                'Calendar.description',
                'Calendar.container_id',
            ],
            'conditions' => [
                'Calendar.container_id' => $containerIds,
            ],
            'order'      => [
                'Calendar.name' => 'ASC',
            ],
        ];
        $this->Paginator->settings = array_merge($this->Paginator->settings, $query);
        $calendars = $this->Paginator->paginate();
        $this->set(compact(['calendars']));
        $this->set('_serialize', ['calendars']);
    }

    public function add()
    {
        if ($this->hasRootPrivileges === true) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
            $tenants = $this->Tenant->tenantsByContainerId($containerIds, 'list', 'container_id');
        } else {
            $tenants = $this->Tenant->tenantsByContainerId($this->getWriteContainers(), 'list', 'container_id');
        }
        $this->set(compact(['tenants']));
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->request->data('CalendarHoliday')) {
                $this->Frontend->setJson('events', $this->request->data['CalendarHoliday']);
                $this->request->data['CalendarHoliday'] = $this->Calendar->prepareForSave($this->request->data['CalendarHoliday']);
            }
            if ($this->Calendar->saveAll($this->request->data)) {
                if ($this->request->ext == 'json') {
                    $this->serializeId();

                    return;
                } else {
                    $this->setFlash(__('Calendar successfully saved.'));
                    $this->redirect(['action' => 'index']);
                }
            } else {
                if ($this->request->ext == 'json') {
                    $this->serializeErrorMessage();

                    return;
                } else {
                    $this->setFlash(__('Could not save data'), false);
                }
            }
        }
    }

    public function edit($id = null)
    {
        if (!$this->Calendar->exists($id)) {
            throw new NotFoundException(__('Invalid calendar'));
        }

        if ($this->hasRootPrivileges === true) {
            $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
            $tenants = $this->Tenant->tenantsByContainerId($containerIds, 'list', 'container_id');
        } else {
            $tenants = $this->Tenant->tenantsByContainerId($this->getWriteContainers(), 'list', 'container_id');
        }
        $calendar = $this->Calendar->findById($id);

        if (!$this->allowedByContainerId($calendar['Calendar']['container_id'])) {
            $this->render403();

            return;
        }
        $events = Set::combine($calendar['CalendarHoliday'], '{n}.date', '{n}.{(name|default_holiday)}');
        if ($this->request->data('CalendarHoliday')) {
            $events = $this->request->data('CalendarHoliday');
            $this->request->data['CalendarHoliday'] = $this->Calendar->prepareForSave($this->request->data['CalendarHoliday']);
        }

        $this->Frontend->setJson('events', $events);
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Calendar->set($this->request->data);
            if ($this->Calendar->validates()) {
                //delete old entries for holidays
                $this->CalendarHoliday->deleteAll([
                    'CalendarHoliday.calendar_id' => $id],
                    false
                );
            }
            if ($this->Calendar->saveAll($this->request->data)) {
                $this->setFlash(__('Calendar successfully saved.'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not save data'), false);
            }
        }

        $data = [
            'tenants'  => $tenants,
            'calendar' => $calendar,
        ];
        $this->set($data);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        if (!$this->Calendar->exists($id)) {
            throw new NotFoundException(__('Invalid calendar'));
        }
        $calendar = $this->Calendar->findById($id);
        if (!$this->allowedByContainerId($calendar['Calendar']['container_id'])) {
            $this->render403();

            return;
        }
        if ($this->Calendar->delete($id)) {
            $timeperiods = $this->Timeperiod->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Timeperiod.calendar_id' => $id
                ],
                'fields' => [
                    'Timeperiod.id'
                ]
            ]);

            foreach($timeperiods as $timeperiod){
                $this->Timeperiod->id = $timeperiod['Timeperiod']['id'];
                $this->Timeperiod->saveField('calendar_id', 0);
            }

            $this->setFlash(__('Calendar deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete calendar'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadHolidays($countryCode = 'de')
    {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }
        $holiday = new CalendarHolidays();
        $holidays = $holiday->getHolidays($countryCode);
        $this->set(compact(['holidays']));
        $this->set('_serialize', ['holidays']);
    }

    public function mass_delete()
    {
        $args_are_valid = true;
        $args = func_get_args();
        foreach ($args as $arg) {
            if (!is_numeric($arg)) {
                $args_are_valid = false;
            }
        }
        if ($args_are_valid) {
            $this->Calendar->deleteAll('Calendar.id IN ('.implode(',', $args).')');

            $timeperiods = $this->Timeperiod->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Timeperiod.calendar_id' => $args
                ],
                'fields' => [
                    'Timeperiod.id'
                ]
            ]);

            foreach($timeperiods as $timeperiod){
                $this->Timeperiod->id = $timeperiod['Timeperiod']['id'];
                $this->Timeperiod->saveField('calendar_id', 0);
            }

            $this->setFlash(__('The calendars were successfully deleted.'));
        } else {
            $this->setFlash(__('Could not delte the calendars. The given arguments were invalid.'), false);
        }
        $this->redirect(['action' => 'index']);
    }
}
