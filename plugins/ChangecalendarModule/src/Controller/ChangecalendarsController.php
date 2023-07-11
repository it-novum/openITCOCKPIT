<?php
declare(strict_types=1);

namespace ChangecalendarModule\Controller;

use App\itnovum\openITCOCKPIT\Filter\ChangecalendarsFilter;
use App\Model\Table\CalendarsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\TimeperiodsTable;
use App\Model\Table\WidgetsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use ChangecalendarModule\Controller\AppController;
use ChangecalendarModule\Model\Table\ChangecalendarsTable;
use DateTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CalendarFilter;

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
            /*
            $calendar['allowEdit'] = $this->hasPermission('edit', 'changecalendars');
            if ($this->hasRootPrivileges === false && $calendar['allowEdit'] === true) {
                $calendar['allowEdit'] = $this->allowedByContainerId($calendar['container_id']);
            }
            */
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
     * View method
     *
     * @param string|null $id Changecalendar id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
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
            $events = $this->request->getData('events');

            /*
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
            */


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
     * Edit method
     *
     * @param string|null $id Changecalendar id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
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
            //Fix name for json/js
            foreach ($events as $index => $event) {
                $events[$index]['title'] = $event['name'];
                $events[$index]['start'] = $event['begin'];
                $events[$index]['end'] = $event['end'];
            }

            unset($changeCalendar['changecalendar_events']);

            $this->set('changeCalendar', $changeCalendar);
            $this->set('events', $events);
            $this->viewBuilder()->setOption('serialize', ['changeCalendar', 'events']);
            return;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData('changeCalendar');
            $events = $this->request->getData('events');
            $data['calendar_holidays'] = [];

            foreach ($events as $event) {
                if (!isset($event['title']) || !isset($event['start']) || !isset($event['end'])) {
                    continue;
                }

                $tmpEvent = [
                    'name'  => $event['title'],
                    'begin' => (new DateTime((string)($event['start'])))->format('Y-m-d H:i:s'),
                    'end'   => (new DateTime((string)($event['end'])))->format('Y-m-d H:i:s'),
                ];

                if (isset($event['id']) && $event['changecalendar_id']) {
                    $tmpEvent['id'] = $event['id'];
                    $tmpEvent['changecalendar_id'] = $event['changecalendar_id'];
                }

                $data['changecalendar_events'][] = $tmpEvent;
            }


            $changeCalendar = $ChangecalendarsTable->get($id, [
                'contain' => 'ChangecalendarEvents'
            ]);

            $Entity = $ChangecalendarsTable->find()
                ->where([
                    'id' => $id
                ])
                ->contain(['ChangecalendarEvents'])
                ->firstOrFail();

            $Entity['changecalendar_events'] = $data['changecalendar_events'];
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
     * Delete method
     *
     * @param string|null $id Changecalendar id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
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

    // For acl....
    public function widget(): void {

        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WidgetsTable $WidgetsTable */
        $WidgetsTable = TableRegistry::getTableLocator()->get('Widgets');
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if ($this->request->is('get')) {
            $widgetId = (int)$this->request->getQuery('widgetId', 0);
            if (!$WidgetsTable->existsById($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $widget = $WidgetsTable->getWidgetByIdAsCake2($widgetId);

            //Check host permissions
            $jsonData = (array)json_decode($widget['Widget']['json_data'], true);
            $changeCalendarId = $jsonData['changecalendar_id'];
            if ($changeCalendarId > 0) {
                $this->set('changecalendar_id', $changeCalendarId);
            }

            if (!$this->isApiRequest()) {
                //Only ship HTML template for angular
                return;
            }

            /** @var ChangecalendarsTable $ChangecalendarsTable */
            $ChangecalendarsTable = TableRegistry::getTableLocator()->get('ChangecalendarModule.Changecalendars');

            if (!$ChangecalendarsTable->existsById($changeCalendarId)) {
                throw new NotFoundException(__('Invalid changeCalendar'));
            }

            $changeCalendar = $ChangecalendarsTable->getCalendarByIdForEdit($changeCalendarId);

            if (!$this->allowedByContainerId($changeCalendar['container_id'])) {
                $this->render403();
                return;
            }
            $events = $changeCalendar['changecalendar_events'];
            //Fix name for json/js
            foreach ($events as $index => $event) {
                $events[$index]['title'] = $event['name'];
                $events[$index]['start'] = $event['begin'];
                $events[$index]['end'] = $event['end'];
            }

            unset($changeCalendar['changecalendar_events']);

            $this->set('changeCalendar', $changeCalendar);
            $this->set('events', $events);
            $this->viewBuilder()->setOption('serialize', ['changeCalendar', 'events', 'changecalendar_id']);
            return;
        }


        if ($this->request->is('post')) {
            $hostId = (int)$this->request->getData('host_id', 0);

            if ($hostId === 0) {
                $hostId = null;
            }

            $widgetId = (int)$this->request->getData('Widget.id', 0);

            if (!$WidgetsTable->existsById($widgetId)) {
                throw new \RuntimeException('Invalid widget id');
            }

            $json = [
                'changecalendar_id' => (int)$this->request->getData('changecalendar_id', 0)
            ];

            $widget = $WidgetsTable->get($widgetId);
            $widget->set('host_id', $hostId);
            $widget->set('json_data', json_encode($json));

            $WidgetsTable->save($widget);
            if ($widget->hasErrors()) {
                $this->serializeCake4ErrorMessage($widget);
                return;
            } else {
                $this->set('changecalendar_id', $hostId);
                $this->viewBuilder()->setOption('serialize', ['changecalendar_id']);
                return;
            }
        }
        throw new MethodNotAllowedException();
    }
}
