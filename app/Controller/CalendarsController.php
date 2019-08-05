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
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CalendarFilter;

App::uses('CalendarHolidays', 'Vendor/Date');


/**
 * @property Calendar $Calendar
 * @property Tenant $Tenant
 * @property CalendarHoliday $CalendarHoliday
 * @property RequestHandlerComponent $RequestHandler
 * @property PaginatorComponent $Paginator
 */
class CalendarsController extends AppController {

    public $layout = 'blank';

    /**
     * Lists the existing configurations to load and edit them.
     */
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


        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containerIds = $this->MY_RIGHTS;
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
        if (!$this->Calendar->exists($id)) {
            throw new NotFoundException(__('Invalid calendar'));
        }
        $calendar = $this->Calendar->findById($id);
        if (!$this->allowedByContainerId($calendar['Calendar']['container_id'])) {
            $this->render403();

            return;
        }
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $query = $TimeperiodsTable->query();
        if ($this->Calendar->delete($id)) {
            $timeperiods = $TimeperiodsTable->getTimeperiodByCalendarIdsAsList($id);
            foreach ($timeperiods as $timeperiodId => $timeperiodName) {
                $query->update()
                    ->set(['calendar_id' => 0])
                    ->where(['id' => $timeperiodId])
                    ->execute();

            }
            $this->setFlash(__('Calendar deleted'));
            $this->redirect(['action' => 'index']);
        }
        $this->setFlash(__('Could not delete calendar'), false);
        $this->redirect(['action' => 'index']);
    }

    public function loadHolidays($countryCode = 'de') {
        if (!$this->request->is('ajax')) {
     //       throw new MethodNotAllowedException();
        }
        $holiday = new CalendarHolidays();
        $holidays = $holiday->getHolidays($countryCode);
        $this->set(compact(['holidays']));
        $this->set('_serialize', ['holidays']);
    }

    public function mass_delete() {
        $args_are_valid = true;
        $args = func_get_args();
        foreach ($args as $arg) {
            if (!is_numeric($arg)) {
                $args_are_valid = false;
            }
        }
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        $query = $TimeperiodsTable->query();
        if ($args_are_valid) {
            $this->Calendar->deleteAll('Calendar.id IN (' . implode(',', $args) . ')');
            $timeperiods = $TimeperiodsTable->getTimeperiodByCalendarIdsAsList($args);
            foreach ($timeperiods as $timeperiodId => $timeperiodName) {
                $query->update()
                    ->set(['calendar_id' => 0])
                    ->where(['id' => $timeperiodId])
                    ->execute();

            }
            $this->setFlash(__('The calendars were successfully deleted.'));
        } else {
            $this->setFlash(__('Could not delete the calendars. The given arguments were invalid.'), false);
        }
        $this->redirect(['action' => 'index']);
    }

    public function loadCalendarsByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $containerId = $this->request->query('containerId');

        $calendars = Api::makeItJavaScriptAble(
            $this->Calendar->calendarsByContainerId($containerId, 'list')
        );

        $this->set('calendars', $calendars);
        $this->set('_serialize', ['calendars']);
    }
}
