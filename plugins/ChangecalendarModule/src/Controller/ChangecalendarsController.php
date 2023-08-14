<?php
declare(strict_types=1);

namespace ChangecalendarModule\Controller;

use App\itnovum\openITCOCKPIT\Filter\ChangecalendarsFilter;
use App\Model\Table\HostsTable;
use App\Model\Table\WidgetsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use ChangecalendarModule\Model\Table\ChangecalendarEventsTable;
use ChangecalendarModule\Model\Table\ChangecalendarsTable;
use DateTime;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * Changecalendars Controller
 *
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ChangecalendarsController extends AppController {
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');
        $CalendarFilter = new ChangecalendarsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $CalendarFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        $changecalendars = $ChangecalendarsTable->getChangecalendarsIndex($CalendarFilter, $PaginateOMat, $MY_RIGHTS);
        $all_changecalendars = [];
        foreach ($changecalendars as $calendar) {
            $calendar['allowEdit'] = true;
            $all_changecalendars[] = $calendar;
        }

        $this->set('all_changecalendars', $all_changecalendars);
        $toJson = ['all_changecalendars', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_changecalendars', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->getData('Changecalendar');

            /** @var ChangecalendarsTable $ChangecalendarsTable */
            $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');
            $Entity = $ChangecalendarsTable->newEntity($data);
            $ChangecalendarsTable->save($Entity);
            if ($Entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $Entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                //No errors
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($Entity); // REST API ID serialization
                    return;
                }
            }
            $this->set('calendar', $Entity);
            $this->viewBuilder()->setOption('serialize', ['calendar']);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Changecalendar id.
     */
    public function view($id = null): void {
        if (!$this->request->is('get')) {
            throw new MethodNotAllowedException('Only GET is allowed');
        }

        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        if (!$ChangecalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid changeCalendar'));
        }

        $changeCalendar = $ChangecalendarsTable->getCalendarByIdForEdit($id);

        if (!$this->allowedByContainerId($changeCalendar['container_id'])) {
            $this->render403();
            return;
        }

        $events = $changeCalendar['changecalendar_events'];

        unset($changeCalendar['changecalendar_events']);

        $this->set('changeCalendar', $changeCalendar);
        $User = new User($this->getUser());
        foreach($events as $index => $event) {
            $events[$index]['start']->setTimeZone($User->getTimezone());
            $events[$index]['end']->setTimeZone($User->getTimezone());
        }
        $this->set('events', $events);
        $this->viewBuilder()->setOption('serialize', ['changeCalendar', 'events']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Changecalendar id.
     */
    public function edit($id = null): void {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        if (!$ChangecalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid changeCalendar'));
        }

        $changeCalendar = $ChangecalendarsTable->getCalendarByIdForEdit($id);

        if (!$this->allowedByContainerId($changeCalendar['container_id'])) {
            $this->render403();
            return;
        }

        if ($this->request->is('get')) {
            $events = $changeCalendar['changecalendar_events'];

            $User = new User($this->getUser());
            foreach($events as $index => $event) {
                $events[$index]['start']->setTimeZone($User->getTimezone());
                $events[$index]['end']->setTimeZone($User->getTimezone());
            }

            unset($changeCalendar['changecalendar_events']);
            $this->set('changeCalendar', $changeCalendar);
            $this->set('events', $events);
            $this->viewBuilder()->setOption('serialize', ['changeCalendar', 'events']);
            return;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData('changeCalendar');

            $Entity = $ChangecalendarsTable->find()
                ->where([
                    'id' => $id
                ])
                ->firstOrFail();

            $Entity = $ChangecalendarsTable->patchEntity($Entity, $data);
            $ChangecalendarsTable->save($Entity);
            if ($Entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $Entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('changeCalendar', $Entity);
            $this->viewBuilder()->setOption('serialize', ['changeCalendar']);

        }
    }

    /**
     * I will delete an event from the database.
     * @param $id
     * @return void
     */
    public function deleteEvent($id = null): void {
        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        if (!$ChangecalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Changecalendar'));
        }

        $event = $this->request->getData('event');

        /** @var ChangecalendarEventsTable $ChangecalendarEventsTable */
        $ChangecalendarEventsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.ChangecalendarEvents');

        $Entity = $ChangecalendarEventsTable->find()
            ->where([
                'id'                => $event['id'],
                'changecalendar_id' => $id
            ])->firstOrFail();
        $ChangecalendarEventsTable->delete($Entity);


        $changeCalendar = $ChangecalendarsTable->getCalendarByIdForEdit($id);
        $this->set('changeCalendar', $changeCalendar);
        $this->viewBuilder()->setOption('serialize', ['changeCalendar']);
    }

    /**
     * I will put the events into the database.
     * @param $id
     * @return void
     * @throws \Exception
     */
    public function events($id = null): void {
        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        if (!$ChangecalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Changecalendar'));
        }

        if ($this->request->is('post')) {
            $event = $this->request->getData('event');

            if (!isset($event['title']) || !isset($event['start']) || !isset($event['end'])) {
                return;
            }

            $tmpEvent = [
                'title'             => $event['title'],
                'start'             => (new DateTime((string)($event['start'])))->format('Y-m-d H:i:s'),
                'end'               => (new DateTime((string)($event['end'])))->format('Y-m-d H:i:s'),
                'description'       => $event['description'] ?? '',
                'id'                => $event['id'] ?? null,
                'changecalendar_id' => $id
            ];


            /** @var ChangecalendarEventsTable $ChangecalendarEventsTable */
            $ChangecalendarEventsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.ChangecalendarEvents');
            if (!empty($event['id'])) {
                $Entity = $ChangecalendarEventsTable->find()->where([
                    'id'                => $event['id'],
                    'changecalendar_id' => $id
                ])->first();
                $Entity = $ChangecalendarEventsTable->patchEntity($Entity, $tmpEvent);
            } else {
                $Entity = $ChangecalendarEventsTable->newEntity($tmpEvent);
            }

            $ChangecalendarEventsTable->save($Entity);
        }

        $changeCalendar = $ChangecalendarsTable->getCalendarByIdForEdit($id);
        $this->set('changeCalendar', $changeCalendar);
        $this->viewBuilder()->setOption('serialize', ['changeCalendar']);
    }

    /**
     * I will delete an entire change calendar.
     * @param $id
     * @return void
     */
    public function delete($id = null): void {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var ChangecalendarsTable $ChangecalendarsTable */
        $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

        if (!$ChangecalendarsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Changecalendar'));
        }

        $calendar = $ChangecalendarsTable->get($id);

        if (!$this->allowedByContainerId($calendar->get('container_id'))) {
            $this->render403();
            return;
        }

        if ($ChangecalendarsTable->delete($calendar)) {
            $this->set('success', true);
            $this->set('message', __('Changecalendar deleted successfully'));
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->set('message', __('Issue while deleting Changecalendar'));
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }

    /**
     * I will return the correct calendars and events to show for the widget.
     * @return void
     */
    public function widget(): void {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->getQuery('widgetId', 0);
            if (!$WidgetsTable->existsById($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $Entity = $WidgetsTable->getWidgetByIdAsCake2($widgetId);

            //Check host permissions
            $jsonData = (array)json_decode((string)($Entity['Widget']['json_data'] ?? '[]'), true);
            $changeCalendarIds = $jsonData['changecalendar_ids'];

            if (!$this->isApiRequest()) {
                //Only ship HTML template for angular
                return;
            }

            /** @var ChangecalendarsTable $ChangecalendarsTable */
            $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

            $changeCalendars = [];
            foreach ($changeCalendarIds as $changeCalendarId) {
                if (!$ChangecalendarsTable->existsById($changeCalendarId)) {
                    continue;
                }
                $editCalendar = $ChangecalendarsTable->getCalendarByIdForEdit($changeCalendarId);
                if (!$this->allowedByContainerId($editCalendar['container_id'])) {
                    continue;
                }
                $changeCalendars[$changeCalendarId] = $editCalendar;
                foreach ($changeCalendars[$changeCalendarId]['changecalendar_events'] as $index => $event) {
                    $changeCalendars[$changeCalendarId]['changecalendar_events'][$index]['backgroundColor'] = $changeCalendars[$changeCalendarId]['colour'];
                }

            }

            $this->set('changeCalendars', $changeCalendars);
            $this->set('displayType', (string)($jsonData['displayType'] ?? 'month'));
            $this->viewBuilder()->setOption('serialize', ['changeCalendars', 'displayType', 'events', 'changecalendar_id']);
            return;
        }

        if ($this->request->is('post')) {
            $widgetId = (int)$this->request->getData('Widget.id', 0);
            if (!$WidgetsTable->existsById($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $json = [
                'changecalendar_ids' => (array)$this->request->getData('changecalendar_ids', [0]),
                'displayType'        => (string)$this->request->getData('displayType', 'month')
            ];

            $Entity = $WidgetsTable->get($widgetId);
            $Entity->set('json_data', json_encode($json));

            $WidgetsTable->save($Entity);
            if ($Entity->hasErrors()) {
                $this->serializeCake4ErrorMessage($Entity);
                return;
            }
            $this->viewBuilder()->setOption('serialize', ['changecalendar_id']);
            return;
        }
        throw new MethodNotAllowedException();
    }
}
