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
use App\Model\Table\TimeperiodsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\Holidays;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CalendarFilter;


/**
 * Class CalendarsController
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
     * @param int|null $id
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $CalendarsTable CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');

        if (!$CalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid calendar'));
        }

        $calendar = $CalendarsTable->getCalendarByIdForEdit($id);

        if (!$this->allowedByContainerId($calendar['container_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get')) {
            $events = $calendar['calendar_holidays'];
            //Fix name for json/js
            foreach ($events as $index => $event) {
                $events[$index]['title'] = $event['name'];
                $events[$index]['start'] = $event['date'];
                if ($events[$index]['default_holiday'] === true) {
                    $events[$index]['className'] = 'bg-color-magenta';
                } else {
                    $events[$index]['className'] = 'bg-color-pinkDark';
                }
            }

            unset($calendar['calendar_holidays']);

            $this->set('calendar', $calendar);
            $this->set('events', $events);
            $this->set('_serialize', ['calendar', 'events']);
            return;
        }

        if ($this->request->is('post')) {
            $data = $this->request->data('Calendar');
            $events = $this->request->data('events');
            $data['calendar_holidays'] = [];
            foreach ($events as $event) {
                if (!isset($event['title']) || !isset($event['default_holiday']) || !isset($event['start'])) {
                    continue;
                }

                $tmpEvent = [
                    'name'            => $event['title'],
                    'default_holiday' => (int)$event['default_holiday'],
                    'date'            => $event['start']
                ];

                if (isset($event['id']) && $event['calendar_id']) {
                    $tmpEvent['id'] = $event['id'];
                    $tmpEvent['calendar_id'] = $event['calendar_id'];

                }

                $data['calendar_holidays'][] = $tmpEvent;
            }

            $Entity = $CalendarsTable->get($id);
            $calendar = $CalendarsTable->patchEntity($Entity, $data);

            $CalendarsTable->save($Entity);
            if ($Entity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $Entity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }

            $this->set('calendar', $Entity);
            $this->set('_serialize', ['calendar']);

            return;
        }
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


    /**
     * @throws ReflectionException
     */
    public function loadCountryList() {
        $holiday = new Holidays();

        $countries = $holiday->getCountries();
        $this->set('countries', $countries);
        $this->set('_serialize', ['countries']);
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
