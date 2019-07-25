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

use App\Model\Table\HostsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\NotificationsControllerRequest;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\NotificationsOverviewControllerRequest;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HostNotificationConditions;
use itnovum\openITCOCKPIT\Core\ServiceNotificationConditions;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Database\ScrollIndex;

/**
 * Class NotificationsController
 * @property AppPaginatorComponent $Paginator
 * @property AppAuthComponent $Auth
 * @property DbBackend $DbBackend
 */
class NotificationsController extends AppController {

    public $uses = [
        MONITORING_NOTIFICATION_HOST,
        MONITORING_NOTIFICATION_SERVICE,
        'Host',
        'Service'
    ];

    public $layout = 'blank';

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        session_write_close();

        $AngularNotificationsOverviewControllerRequest = new NotificationsOverviewControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $AngularNotificationsOverviewControllerRequest->getPage());

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        //Process conditions
        $Conditions = new HostNotificationConditions();
        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setFrom($AngularNotificationsOverviewControllerRequest->getFrom());
        $Conditions->setTo($AngularNotificationsOverviewControllerRequest->getTo());
        $Conditions->setOrder($AngularNotificationsOverviewControllerRequest->getOrderForPaginator('NotificationHosts.start_time', 'desc'));
        $Conditions->setStates($AngularNotificationsOverviewControllerRequest->getHostStates());
        $Conditions->setConditions($AngularNotificationsOverviewControllerRequest->getHostFilters());

        //Query notification records
        $NotificationHostsTable = $this->DbBackend->getNotificationHostsTable();

        $all_notifications = [];
        foreach ($NotificationHostsTable->getNotifications($Conditions, $PaginateOMat) as $notification) {
            $NotificationHost = new itnovum\openITCOCKPIT\Core\Views\NotificationHost($notification, $UserTime);
            $Host = new itnovum\openITCOCKPIT\Core\Views\Host($notification['Hosts']);
            $Command = new itnovum\openITCOCKPIT\Core\Views\Command($notification['Commands']);
            $Contact = new itnovum\openITCOCKPIT\Core\Views\Contact($notification['Contacts']);
            $all_notifications[] = [
                'NotificationHost' => $NotificationHost->toArray(),
                'Host'             => $Host->toArray(),
                'Command'          => $Command->toArray(),
                'Contact'          => $Contact->toArray()
            ];
        }

        $this->set('all_notifications', $all_notifications);

        $toJson = ['all_notifications', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_notifications', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function services() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        session_write_close();

        $AngularNotificationsOverviewControllerRequest = new NotificationsOverviewControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $AngularNotificationsOverviewControllerRequest->getPage());

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        //Process conditions
        $Conditions = new ServiceNotificationConditions();
        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setFrom($AngularNotificationsOverviewControllerRequest->getFrom());
        $Conditions->setTo($AngularNotificationsOverviewControllerRequest->getTo());
        $Conditions->setOrder($AngularNotificationsOverviewControllerRequest->getOrderForPaginator('NotificationServices.start_time', 'desc'));
        $Conditions->setStates($AngularNotificationsOverviewControllerRequest->getServiceStates());
        $Conditions->setConditions($AngularNotificationsOverviewControllerRequest->getServiceFilters());

        //Query notification records
        $NotificationServicesTable = $this->DbBackend->getNotificationServicesTable();

        $all_notifications = [];
        foreach ($NotificationServicesTable->getNotifications($Conditions, $PaginateOMat) as $notification) {
            $NotificationService = new itnovum\openITCOCKPIT\Core\Views\NotificationService($notification, $UserTime);
            $Service = new itnovum\openITCOCKPIT\Core\Views\Service($notification['Services'], $notification['servicename']);
            $Host = new itnovum\openITCOCKPIT\Core\Views\Host($notification['Hosts']);
            $Command = new itnovum\openITCOCKPIT\Core\Views\Command($notification['Commands']);
            $Contact = new itnovum\openITCOCKPIT\Core\Views\Contact($notification['Contacts']);
            $all_notifications[] = [
                'NotificationService' => $NotificationService->toArray(),
                'Service'             => $Service->toArray(),
                'Host'                => $Host->toArray(),
                'Command'             => $Command->toArray(),
                'Contact'             => $Contact->toArray()
            ];
        }

        $this->set('all_notifications', $all_notifications);

        $toJson = ['all_notifications', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_notifications', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param null $id
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function hostNotification($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var \App\Model\Entity\Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($id);
        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
            $this->render403();
            return;
        }

        session_write_close();

        $AngularNotificationsOverviewControllerRequest = new NotificationsOverviewControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $AngularNotificationsOverviewControllerRequest->getPage());

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        //Process conditions
        $Conditions = new HostNotificationConditions();
        $Conditions->setContainerIds($this->MY_RIGHTS);
        $Conditions->setFrom($AngularNotificationsOverviewControllerRequest->getFrom());
        $Conditions->setTo($AngularNotificationsOverviewControllerRequest->getTo());
        $Conditions->setOrder($AngularNotificationsOverviewControllerRequest->getOrderForPaginator('NotificationHosts.start_time', 'desc'));
        $Conditions->setStates($AngularNotificationsOverviewControllerRequest->getHostStates());
        $Conditions->setConditions($AngularNotificationsOverviewControllerRequest->getHostFilters());
        $Conditions->setHostUuid($host->get('uuid'));

        //Query notification records
        $NotificationHostsTable = $this->DbBackend->getNotificationHostsTable();

        $all_notifications = [];
        foreach ($NotificationHostsTable->getNotifications($Conditions, $PaginateOMat) as $notification) {
            $NotificationHost = new itnovum\openITCOCKPIT\Core\Views\NotificationHost($notification, $UserTime);
            $Host = new itnovum\openITCOCKPIT\Core\Views\Host($notification['Hosts']);
            $Command = new itnovum\openITCOCKPIT\Core\Views\Command($notification['Commands']);
            $Contact = new itnovum\openITCOCKPIT\Core\Views\Contact($notification['Contacts']);
            $all_notifications[] = [
                'NotificationHost' => $NotificationHost->toArray(),
                'Host'             => $Host->toArray(),
                'Command'          => $Command->toArray(),
                'Contact'          => $Contact->toArray()
            ];
        }

        $this->set('all_notifications', $all_notifications);

        $toJson = ['all_notifications', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_notifications', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    /**
     * @param int|null $service_id
     * @deprecated
     */
    public function serviceNotification($service_id = null) {
        if (!$this->Service->exists($service_id) && $service_id !== null) {
            throw new NotFoundException(__('Invalid service'));
        }

        if (!$this->isAngularJsRequest() && $service_id === null) {
            //Service for .html requests
            return;
        }

        //Service for .json requests
        $service = $this->Service->find('first', [
            'recursive'  => -1,
            'fields'     => [
                'Service.id',
                'Service.uuid',
                'Service.name',
                'Service.service_type',
                'Service.service_url'
            ],
            'contain'    => [
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.uuid',
                        'Host.address'
                    ],
                    'Container',
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name',
                    ],
                ],
            ],
            'conditions' => [
                'Service.id' => $service_id,
            ],
        ]);

        if (!$this->allowedByContainerId(Hash::extract($service, 'Host.Container.{n}.HostsToContainer.container_id'))) {
            $this->render403();
            return;
        }

        $AngularNotificationsControllerRequest = new NotificationsControllerRequest($this->request);


        //Process conditions
        $Conditions = new ServiceNotificationConditions();
        $Conditions->setLimit($this->Paginator->settings['limit']);
        $Conditions->setFrom($AngularNotificationsControllerRequest->getFrom());
        $Conditions->setTo($AngularNotificationsControllerRequest->getTo());
        $Conditions->setOrder($AngularNotificationsControllerRequest->getOrderForPaginator('NotificationService.start_time', 'desc'));
        $Conditions->setServiceUuid($service['Service']['uuid']);
        $Conditions->setStates($AngularNotificationsControllerRequest->getServiceStates());


        $query = $this->NotificationService->getQuery($Conditions, $AngularNotificationsControllerRequest->getServiceFilters());

        $this->Paginator->settings = $query;
        $this->Paginator->settings['page'] = $AngularNotificationsControllerRequest->getPage();

        $ScrollIndex = new ScrollIndex($this->Paginator, $this);
        if ($this->isScrollRequest()) {
            $notifications = $this->NotificationService->find('all', $this->Paginator->settings);
            $ScrollIndex->determineHasNextPage($notifications);
            $ScrollIndex->scroll();
        } else {
            $notifications = $this->Paginator->paginate(
                $this->NotificationService->alias,
                [],
                [key($this->Paginator->settings['order'])]
            );
        }

        $all_notifications = [];
        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        foreach ($notifications as $notification) {
            $NotificationService = new itnovum\openITCOCKPIT\Core\Views\NotificationService($notification, $UserTime);
            $Service = new itnovum\openITCOCKPIT\Core\Views\Service($notification);
            $Host = new itnovum\openITCOCKPIT\Core\Views\Host($notification);
            $Command = new itnovum\openITCOCKPIT\Core\Views\Command($notification['Command']);
            $Contact = new itnovum\openITCOCKPIT\Core\Views\Contact($notification['Contact']);
            $all_notifications[] = [
                'NotificationService' => $NotificationService->toArray(),
                'Service'             => $Service->toArray(),
                'Host'                => $Host->toArray(),
                'Command'             => $Command->toArray(),
                'Contact'             => $Contact->toArray()
            ];
        }

        $this->set(compact(['all_notifications']));
        $toJson = ['all_notifications', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_notifications', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

}
