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

use App\Model\Table\CalendarsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\TimeperiodsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Holidays;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CalendarFilter;


/**
 * @property AppPaginatorComponent $Paginator
 * @property DbBackend $DbBackend
 */
class CalendarsController extends AppController {

    public $layout = 'blank';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $CalendarsTable CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');
        $CalendarFilter = new CalendarFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $CalendarFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        $calendars = $CalendarsTable->getCalendarsIndex($CalendarFilter, $PaginateOMat, $MY_RIGHTS);
        $all_calendars = [];
        foreach ($calendars as $calendar) {
            $calendar['allowEdit'] = $this->hasPermission('edit', 'calendars');
            if ($this->hasRootPrivileges === false && $calendar['allowEdit'] === true) {
                $calendar['allowEdit'] = $this->allowedByContainerId($calendar['container_id']);
            }

            $all_calendars[] = $calendar;
        }

        $this->set('all_calendars', $all_calendars);
        $toJson = ['all_calendars', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_calendars', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }


    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data('Calendar');
            $events = $this->request->data('events');
            $data['calendar_holidays'] = [];
            foreach ($events as $event) {
                if (!isset($event['title']) || !isset($event['default_holiday']) || !isset($event['start'])) {
                    continue;
                }

                $data['calendar_holidays'][] = [
                    'name'            => $event['title'],
                    'default_holiday' => (int)$event['default_holiday'],
                    'date'            => $event['start']
                ];
            }

            /** @var $CalendarsTable CalendarsTable */
            $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');

            $Entity = $CalendarsTable->newEntity($data);
            $CalendarsTable->save($Entity);
            if ($Entity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $Entity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors
                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($Entity); // REST API ID serialization
                    return;
                }
            }
            $this->set('calendar', $Entity);
            $this->set('_serialize', ['calendar']);
        }
    }

    /**
     * @param null $id
     * @deprecated
     */
    public function edit($id = null) {
        if (!$this->Calendar->exists($id)) {
            throw new NotFoundException(__('Invalid calendar'));
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containerIds = $this->MY_RIGHTS;
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
                    'CalendarHoliday.calendar_id' => $id
                ],
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

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $CalendarsTable CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');

        if (!$CalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid calendar'));
        }

        $calendar = $CalendarsTable->get($id);

        if (!$this->allowedByContainerId($calendar->get('container_id'))) {
            $this->render403();
            return;
        }

        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $query = $TimeperiodsTable->query();
        if ($CalendarsTable->delete($calendar)) {
            $timeperiods = $TimeperiodsTable->getTimeperiodByCalendarIdsAsList($id);
            foreach ($timeperiods as $timeperiodId => $timeperiodName) {
                $query->update()
                    ->set(['calendar_id' => 0])
                    ->where(['id' => $timeperiodId])
                    ->execute();

            }

            $this->set('success', true);
            $this->set('message', __('Calendar deleted successfully'));
            $this->set('_serialize', ['success', 'message']);
            return;
        }

        $this->response->statusCode(400);
        $this->set('success', false);
        $this->set('message', __('Issue while deleting calendar'));
        $this->set('_serialize', ['success', 'message']);
    }

    /**
     * @param string $countryCode
     * @throws ReflectionException
     */
    public function loadHolidays($countryCode = 'de') {
        $holiday = new Holidays();

        $holidays = $holiday->getHolidays($countryCode);
        $this->set('holidays', $holidays);
        $this->set('_serialize', ['holidays']);
    }

    public function loadCalendarsByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $CalendarsTable CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');

        $containerId = $this->request->query('containerId');

        $calendars = Api::makeItJavaScriptAble(
            $CalendarsTable->getCalendarsByContainerIds($containerId, 'list')
        );

        $this->set('calendars', $calendars);
        $this->set('_serialize', ['calendars']);
    }
}
