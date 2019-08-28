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
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\HostescalationsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServiceescalationsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\Permissions\ContactContainersPermissions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ContactsFilter;
use itnovum\openITCOCKPIT\Ldap\LdapClient;


/**
 * @property Contact $Contact
 * @property Container $Container
 * @property Timeperiod $Timeperiod
 * @property Customvariable $Customvariable
 * @property AppPaginatorComponent $Paginator
 */
class ContactsController extends AppController {

    public $uses = [
        'Contact',
        'Container',
        'Timeperiod',
        'Customvariable',
        'User'
    ];

    public $layout = 'blank';


    public function index() {
        /** @var $SystemsettingsTable SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');

        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            $this->set('isLdapAuth', $SystemsettingsTable->isLdapAuth());
            return;
        }


        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');


        $ContactsFilter = new ContactsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ContactsFilter->getPage());

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }
        $contacts = $ContactsTable->getContactsIndex($ContactsFilter, $PaginateOMat, $MY_RIGHTS);

        $contactsWithContainers = [];
        foreach ($contacts as $key => $contact) {
            $contactsWithContainers[$contact['Contact']['id']] = [];
            foreach ($contact['Container'] as $container) {
                $contactsWithContainers[$contact['Contact']['id']][] = $container['id'];
            }

            $contacts[$key]['Contact']['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $contacts[$key]['Contact']['allow_edit'] = false;
                if (!empty(array_intersect($contactsWithContainers[$contact['Contact']['id']], $this->getWriteContainers()))) {
                    $contacts[$key]['Contact']['allow_edit'] = true;
                }
            }
        }

        $this->set('all_contacts', $contacts);
        $toJson = ['all_contacts', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_contacts', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();

        }

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        if (!$ContactsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid contact'));
        }

        $contact = $ContactsTable->getContactById($id);
        if (!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))) {
            throw new ForbiddenException('403 Forbidden');
        }

        if (!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))) {
            throw new ForbiddenException('403 Forbidden');
        }
        $this->set('contact', $contact);
        $this->set('_serialize', ['contact']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            /** @var $ContactsTable ContactsTable */
            $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
            $this->request->data['Contact']['uuid'] = \itnovum\openITCOCKPIT\Core\UUID::v4();
            $contact = $ContactsTable->newEntity();
            $contact = $ContactsTable->patchEntity($contact, $this->request->data('Contact'));

            $ContactsTable->save($contact);
            if ($contact->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $contact->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $extDataForChangelog = $ContactsTable->resolveDataForChangelog($this->request->data);

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    'contacts',
                    $contact->id,
                    OBJECT_CONTACT,
                    $this->request->data('Contact.containers._ids'),
                    $User->getId(),
                    $contact->name,
                    array_merge($extDataForChangelog, $this->request->data)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($contact); // REST API ID serialization
                    return;
                }
            }
            $this->set('contact', $contact);
            $this->set('_serialize', ['contact']);
        }
    }

    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        if (!$ContactsTable->existsById($id)) {
            throw new NotFoundException(__('Contact not found'));
        }

        $contact = $ContactsTable->getContactForEdit($id);
        $contactForChangeLog = $contact;

        if (!$this->allowedByContainerId($contact['Contact']['containers']['_ids'])) {
            $this->render403();
            return;
        }

        if ($this->hasRootPrivileges === false) {
            if (empty(array_intersect($contact['Contact']['containers']['_ids'], $this->getWriteContainers()))) {
                $this->render403();
            }
        }

        $ContactContainersPermissions = new ContactContainersPermissions(
            $contact['Contact']['containers']['_ids'],
            $this->getWriteContainers(),
            $this->hasRootPrivileges
        );

        if ($this->request->is('get') && $this->isAngularJsRequest()) {
            //Return contact information
            $this->set('contact', $contact);
            $this->set('areContainersChangeable', $ContactContainersPermissions->areContainersChangeable());
            $this->set('_serialize', ['contact', 'areContainersChangeable']);
            return;
        }

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            //Update contact data
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $contactEntity = $ContactsTable->get($id);

            if ($ContactContainersPermissions->areContainersChangeable() === false) {
                $contactBeforeEdit = $ContactsTable->getContactForEdit($id);
                //Overwrite post data. User is not permitted to change container ids!
                $this->request->data['Contact']['containers']['_ids'] = $contact['Contact']['containers']['_ids'];
            }

            $contactEntity->setAccess('uuid', false);
            $contactEntity = $ContactsTable->patchEntity($contactEntity, $this->request->data('Contact'));
            $contactEntity->id = $id;
            $ContactsTable->save($contactEntity);
            if ($contactEntity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $contactEntity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    'contacts',
                    $contactEntity->id,
                    OBJECT_CONTACT,
                    $this->request->data('Contact.containers._ids'),
                    $User->getId(),
                    $contactEntity->name,
                    array_merge($ContactsTable->resolveDataForChangelog($this->request->data), $this->request->data),
                    array_merge($ContactsTable->resolveDataForChangelog($contactForChangeLog), $contactForChangeLog)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($contactEntity); // REST API ID serialization
                    return;
                }
            }
            $this->set('contact', $contactEntity);
            $this->set('_serialize', ['contact']);
        }
    }


    public function addFromLdap() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }
    }


    /**
     * @param null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        if (!$ContactsTable->existsById($id)) {
            throw new NotFoundException(__('Contact not found'));
        }

        $contact = $ContactsTable->getContactById($id);

        if (!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))) {
            $this->render403();
            return;
        }

        if (!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))) {
            $this->render403();
            return;
        }


        if (!$ContactsTable->allowDelete($id)) {
            $usedBy = [
                [
                    'baseUrl' => '#',
                    'state'   => 'ContactsUsedBy',
                    'message' => __('Used by other objects'),
                    'module'  => 'Core'
                ]
            ];

            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting contact'));
            $this->set('usedBy', $usedBy);
            $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
            return;
        }


        $contactEntity = $ContactsTable->get($id);
        if ($ContactsTable->delete($contactEntity)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'delete',
                'contacts',
                $id,
                OBJECT_CONTACT,
                Hash::extract($contact['Container'], '{n}.id'),
                $User->getId(),
                $contact['Contact']['name'],
                $contact
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

    public function copy($id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');


        if ($this->request->is('get')) {
            $contacts = $ContactsTable->getContactsForCopy(func_get_args());
            $this->set('contacts', $contacts);
            $this->set('_serialize', ['contacts']);
            return;
        }

        $hasErrors = false;

        if ($this->request->is('post')) {
            $Cache = new KeyValueStore();

            $postData = $this->request->data('data');
            $userId = $this->Auth->user('id');

            foreach ($postData as $index => $contactData) {
                if (!isset($contactData['Contact']['id'])) {
                    //Create/clone contact
                    $sourceContactId = $contactData['Source']['id'];
                    if (!$Cache->has($sourceContactId)) {
                        $sourceContact = $ContactsTable->get($sourceContactId, [
                            'contain' => [
                                'Customvariables',
                                'Containers'
                            ]
                        ])->toArray();
                        foreach ($sourceContact['customvariables'] as $i => $customvariable) {
                            unset($sourceContact['customvariables'][$i]['id']);
                            unset($sourceContact['customvariables'][$i]['contact_id']);
                        }

                        $containers = Hash::extract($sourceContact['containers'], '{n}.id');
                        $sourceContact['containers'] = $containers;

                        $Cache->set($sourceContact['id'], $sourceContact);
                    }

                    $fieldsToCopy = [
                        'user_id',
                        'host_timeperiod_id',
                        'service_timeperiod_id',
                        'host_notifications_enabled',
                        'service_notifications_enabled',
                        'notify_service_recovery',
                        'notify_service_warning',
                        'notify_service_unknown',
                        'notify_service_critical',
                        'notify_service_flapping',
                        'notify_service_downtime',
                        'notify_host_recovery',
                        'notify_host_down',
                        'notify_host_unreachable',
                        'notify_host_flapping',
                        'notify_host_downtime',
                        'host_push_notifications_enabled',
                        'service_push_notifications_enabled'
                    ];

                    $sourceContact = $Cache->get($sourceContactId);

                    $newContactData = [
                        'name'            => $contactData['Contact']['name'],
                        'description'     => $contactData['Contact']['description'],
                        'email'           => $contactData['Contact']['email'],
                        'phone'           => $contactData['Contact']['phone'],
                        'uuid'            => \itnovum\openITCOCKPIT\Core\UUID::v4(),
                        'customvariables' => $sourceContact['customvariables'],
                        'containers'      => [
                            '_ids' => $sourceContact['containers']
                        ]
                    ];
                    foreach ($fieldsToCopy as $fieldToCopy) {
                        $newContactData[$fieldToCopy] = $sourceContact[$fieldToCopy];
                    }

                    $newContactEntity = $ContactsTable->newEntity($newContactData);
                }

                $action = 'copy';
                if (isset($contactData['Contact']['id'])) {
                    //Update existing contact
                    //This happens, if a user copy multiple contacts, and one run into an validation error
                    //All contacts without validation errors got already saved to the database
                    $newContactEntity = $ContactsTable->get($contactData['Contact']['id']);
                    $newContactEntity = $ContactsTable->patchEntity($newContactEntity, $contactData['Contact']);
                    $newContactData = $newContactEntity->toArray();
                    $action = 'edit';
                }
                $ContactsTable->save($newContactEntity);

                $postData[$index]['Error'] = [];
                if ($newContactEntity->hasErrors()) {
                    $hasErrors = true;
                    $postData[$index]['Error'] = $newContactEntity->getErrors();
                } else {
                    //No errors
                    $postData[$index]['Contact']['id'] = $newContactEntity->get('id');

                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $action,
                        'contacts',
                        $postData[$index]['Contact']['id'],
                        OBJECT_CONTACT,
                        [ROOT_CONTAINER],
                        $userId,
                        $newContactEntity->get('name'),
                        ['Contact' => $newContactData]
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

    /**
     * @param int|null $id
     */
    public function usedBy($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var ContactsTable $ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        if (!$ContactsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid contact'));
        }

        $contact = $ContactsTable->get($id);

        $objects = [
            'Contactgroups'      => [],
            'Hosttemplates'      => [],
            'Servicetemplates'   => [],
            'Hosts'              => [],
            'Services'           => [],
            'Hostescalations'    => [],
            'Serviceescalations' => []
        ];

        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges) {
            $MY_RIGHTS = [];
        }

        //Get contact groups
        /** @var ContactgroupsTable $ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        $objects['Contactgroups'] = $ContactgroupsTable->getContactgroupsByContactId($id, $MY_RIGHTS);


        //Check if the contact is used by host or service templates
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        $objects['Hosttemplates'] = $HosttemplatesTable->getHosttemplatesByContactId($id, $MY_RIGHTS, false);

        /** @var $ServicetemplatesTable ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        $objects['Servicetemplates'] = $ServicetemplatesTable->getServicetemplatesByContactId($id, $MY_RIGHTS, false);

        //Checking host and services
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        $objects['Hosts'] = $HostsTable->getHostsByContactId($id, $MY_RIGHTS, false);

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        $objects['Services'] = $ServicesTable->getServicesByContactId($id, $MY_RIGHTS, false);

        //Checking host and service escalations
        /** @var $HostescalationsTable HostescalationsTable */
        $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');
        $objects['Hostescalations'] = $HostescalationsTable->getHostescalationsByContactId($id, $MY_RIGHTS, false);

        /** @var $ServiceescalationsTable ServiceescalationsTable */
        $ServiceescalationsTable = TableRegistry::getTableLocator()->get('Serviceescalations');
        $objects['Serviceescalations'] = $ServiceescalationsTable->getServiceescalationsByContactId($id, $MY_RIGHTS, false);

        $total = 0;
        $total += sizeof($objects['Contactgroups']);
        $total += sizeof($objects['Hosttemplates']);
        $total += sizeof($objects['Servicetemplates']);
        $total += sizeof($objects['Hosts']);
        $total += sizeof($objects['Services']);
        $total += sizeof($objects['Hostescalations']);
        $total += sizeof($objects['Serviceescalations']);

        $this->set('contact', $contact->toArray());
        $this->set('objects', $objects);
        $this->set('total', $total);
        $this->set('_serialize', ['contact', 'objects', 'total']);
    }

    /****************************
     *       AJAX METHODS       *
     ****************************/

    public function loadTimeperiods() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');

        $timePeriods = [];
        if (isset($this->request->data['container_ids'])) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($this->request->data['container_ids']);
            $timePeriods = $TimeperiodsTable->timeperiodsByContainerId($containerIds, 'list');
            $timePeriods = Api::makeItJavaScriptAble($timePeriods);
        }

        $data = [
            'timeperiods' => $timePeriods,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function loadUsersByContainerId() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $users = [];
        if (isset($this->request->data['container_ids'])) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($this->request->data['container_ids']);
            $users = $this->User->usersByContainerId($containerIds, 'list');
            $users = Api::makeItJavaScriptAble($users);
        }

        $data = [
            'users' => $users,
        ];

        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
        }


        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->set('_serialize', ['containers']);
    }


    public function loadCommands() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        $hostPushComamndId = $Commands->getCommandIdByCommandUuid('cd13d22e-acd4-4a67-997b-6e120e0d3153');
        $servicePushComamndId = $Commands->getCommandIdByCommandUuid('c23255b7-5b1a-40b4-b614-17837dc376af');

        $notificationCommands = $Commands->getCommandByTypeAsList(NOTIFICATION_COMMAND);

        $this->set('hostPushComamndId', $hostPushComamndId);
        $this->set('servicePushComamndId', $servicePushComamndId);
        $this->set('notificationCommands', Api::makeItJavaScriptAble($notificationCommands));
        $this->set('_serialize', ['hostPushComamndId', 'servicePushComamndId', 'notificationCommands']);
    }

    /**
     * @throws \FreeDSx\Ldap\Exception\BindException
     */
    public function loadLdapUserByString() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $Ldap = LdapClient::fromSystemsettings($Systemsettings->findAsArraySection('FRONTEND'));

        $samaccountname = (string)$this->request->query('samaccountname');
        $ldapUsers = $Ldap->getUsers($samaccountname);
        $this->set('ldapUsers', $ldapUsers);
        $this->set('_serialize', ['ldapUsers']);
    }
}

