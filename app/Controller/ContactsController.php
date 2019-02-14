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
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\PHPVersionChecker;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\ContactsFilter;


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

    public $layout = 'Admin.default';

    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'Ldap',
        'CustomValidationErrors'
    ];

    public $helpers = [
        'ListFilter.ListFilter',
        'CustomVariables',
        'CustomValidationErrors'
    ];

    public $listFilters = [
        'index' => [
            'fields' => [
                'Contact.name'  => [
                    'label'      => 'Name',
                    'searchType' => 'wildcard',
                ],
                'Contact.email' => [
                    'label'      => 'Email',
                    'searchType' => 'wildcard',
                ],
                'Contact.phone' => [
                    'label'      => 'Pager',
                    'searchType' => 'wildcard',
                ],
            ],
        ],
    ];

    public function index() {
        $this->layout = 'blank';

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
        if (!$this->Contact->exists($id)) {
            throw new NotFoundException(__('Invalid contact'));
        }
        $contact = $this->Contact->findById($id);
        if (!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))) {
            throw new ForbiddenException('403 Forbidden');
        }

        if (!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))) {
            throw new ForbiddenException('403 Forbidden');
        }
        $this->set('contact', $contact);
        $this->set('_serialize', ['contact']);
    }

    public function edit($id = null) {
        $userId = $this->Auth->user('id');
        if (!$this->Contact->exists($id)) {
            throw new NotFoundException(__('Invalid contact'));
        }

        /******** Push Notifications ********/
        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        $hostPushComamndId = $Commands->getCommandIdByCommandUuid('cd13d22e-acd4-4a67-997b-6e120e0d3153');
        $servicePushComamndId = $Commands->getCommandIdByCommandUuid('c23255b7-5b1a-40b4-b614-17837dc376af');

        $this->Frontend->setJson('hostPushComamndId', $hostPushComamndId);
        $this->Frontend->setJson('servicePushComamndId', $servicePushComamndId);

        $contact = $this->Contact->findById($id);

        if ($this->hasRootPrivileges === false) {
            if (empty(array_intersect(Hash::extract($contact, 'Container.{n}.id'), $this->getWriteContainers()))) {
                $this->render403();

            }
        }

        $customFieldsToRefill = [
            'Contact' => [
                'host_notifications_enabled',
                'host_push_notifications_enabled',
                'notify_host_recovery',
                'notify_host_down',
                'notify_host_unreachable',
                'notify_host_flapping',
                'notify_host_downtime',

                'service_notifications_enabled',
                'service_push_notifications_enabled',
                'notify_service_recovery',
                'notify_service_warning',
                'notify_service_critical',
                'notify_service_unknown',
                'notify_service_flapping',
                'notify_service_downtime'
            ]
        ];

        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);


        $this->set('MY_WRITABLE_CONTAINERS', $this->getWriteContainers());

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);

        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        $notification_commands = $CommandsTable->getCommandByTypeAsList(NOTIFICATION_COMMAND);


        $containerIds = Hash::extract($contact, 'Container.{n}.id');

        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Container']['Container'])) {
                $containerIds = $this->request->data['Container']['Container'];
            }

            $ext_data_for_changelog = [
                'HostTimeperiod'    => [
                    'id'   => $this->request->data['Contact']['host_timeperiod_id'],
                    'name' => isset($timeperiods[$this->request->data['Contact']['host_timeperiod_id']]) ? $timeperiods[$this->request->data['Contact']['host_timeperiod_id']] : '',
                ],
                'ServiceTimeperiod' => [
                    'id'   => $this->request->data['Contact']['service_timeperiod_id'],
                    'name' => isset($timeperiods[$this->request->data['Contact']['service_timeperiod_id']]) ? $timeperiods[$this->request->data['Contact']['service_timeperiod_id']] : '',
                ],
            ];

            if (isset($this->request->data['Contact']['HostCommands']) && is_array($this->request->data['Contact']['HostCommands'])) {
                foreach ($this->request->data['Contact']['HostCommands'] as $command_id) {
                    $ext_data_for_changelog['HostCommands'][] = [
                        'id'   => $command_id,
                        'name' => $notification_commands[$command_id],
                    ];
                }
            }
            if (isset($this->request->data['Contact']['ServiceCommands']) && is_array($this->request->data['Contact']['ServiceCommands'])) {
                foreach ($this->request->data['Contact']['ServiceCommands'] as $command_id) {
                    $ext_data_for_changelog['ServiceCommands'][] = [
                        'id'   => $command_id,
                        'name' => $notification_commands[$command_id],
                    ];
                }
            }

            //Checks if the user deletes a customvariable/macro over the trash icon
            if (!isset($this->request->data['Customvariable'])) {
                $this->request->data['Customvariable'] = [];
            }

            $this->Contact->set($this->request->data);
            if ($this->Contact->validates()) {
                $this->Customvariable->deleteAll([
                    'object_id'     => $contact['Contact']['id'],
                    'objecttype_id' => OBJECT_CONTACT,
                ], false);
            }

            $this->Contact->id = $id;
            if ($this->Contact->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_CONTACT,
                    $containerIds,
                    $userId,
                    $this->request->data['Contact']['name'],
                    array_merge($ext_data_for_changelog, $this->request->data),
                    $contact
                );

                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                $this->setFlash(__('<a href="/contacts/edit/%s">Contact</a> successfully saved', $this->Contact->id));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Contact could not be saved'), false);
            }
            if (isset($this->Contact->validationErrors['notify_host_recovery'])) {
                $this->set('validation_host_notification', $this->Contact->validationErrors['notify_host_recovery'][0]);
            }
            if (isset($this->Contact->validationErrors['notify_service_recovery'])) {
                $this->set('validation_service_notification', $this->Contact->validationErrors['notify_service_recovery'][0]);
            }
        }

        if (!$this->request->is('post') && !$this->request->is('put')) {
            $contact['Contact']['HostCommands'] = Hash::extract($contact['HostCommands'], '{n}.id');
            $contact['Contact']['ServiceCommands'] = Hash::extract($contact['ServiceCommands'], '{n}.id');
        }

        $this->request->data = Hash::merge($contact, $this->request->data);

        if ($containerIds !== '') {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerIds);
            $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
            $_users = $this->User->usersByContainerId($containerIds, 'list');
        }

        $this->set(compact(['contact', 'containers', 'notification_commands', 'timeperiods', '_timeperiods', '_users']));
        $this->set('_serialize', ['contact', 'notification_commands', 'timeperiods', '_timeperiods']);
    }

    public function add() {
        $userId = $this->Auth->user('id');

        /******** Push Notifications ********/
        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        $hostPushComamndId = $Commands->getCommandIdByCommandUuid('cd13d22e-acd4-4a67-997b-6e120e0d3153');
        $servicePushComamndId = $Commands->getCommandIdByCommandUuid('c23255b7-5b1a-40b4-b614-17837dc376af');

        $this->Frontend->setJson('hostPushComamndId', $hostPushComamndId);
        $this->Frontend->setJson('servicePushComamndId', $servicePushComamndId);


        $customFieldsToRefill = [
            'Contact' => [
                'host_notifications_enabled',
                'host_push_notifications_enabled',
                'notify_host_recovery',
                'notify_host_down',
                'notify_host_unreachable',
                'notify_host_flapping',
                'notify_host_downtime',

                'service_notifications_enabled',
                'service_push_notifications_enabled',
                'notify_service_recovery',
                'notify_service_warning',
                'notify_service_critical',
                'notify_service_unknown',
                'notify_service_flapping',
                'notify_service_downtime'
            ]
        ];

        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
        }

        $notification_commands = $CommandsTable->getCommandByTypeAsList(NOTIFICATION_COMMAND);
        $timeperiods = $this->Timeperiod->find('list');

        $_timeperiods = [];
        $_users = [];

        $isLdap = false;
        if ($this->getNamedParameter('ldap', 0) == 1) {
            $isLdap = true;
            $this->request->data['Contact']['email'] = $this->getNamedParameter('email', '');
            $this->request->data['Contact']['name'] = $this->getNamedParameter('samaccountname', '');
        }

        $Customvariable = [];
        if (isset($this->request->data['Customvariable'])) {
            $Customvariable = $this->request->data['Customvariable'];
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $containerIds = [];
            if (isset($this->request->data['Container']['Container'])) {
                $containerIds = $this->request->data['Container']['Container'];
                if ($containerIds !== '') {
                    $containerIds = $ContainersTable->resolveChildrenOfContainerIds($containerIds);
                    $_timeperiods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');

                    $_users = $this->User->usersByContainerId($containerIds, 'list');
                }
            }

            $ext_data_for_changelog = [
                'HostTimeperiod'    => [
                    'id'   => $this->request->data['Contact']['host_timeperiod_id'],
                    'name' => isset($timeperiods[$this->request->data['Contact']['host_timeperiod_id']]) ? $timeperiods[$this->request->data['Contact']['host_timeperiod_id']] : '',
                ],
                'ServiceTimeperiod' => [
                    'id'   => $this->request->data['Contact']['service_timeperiod_id'],
                    'name' => isset($timeperiods[$this->request->data['Contact']['service_timeperiod_id']]) ? $timeperiods[$this->request->data['Contact']['service_timeperiod_id']] : '',
                ],
            ];

            if (is_array($this->request->data['Contact']['HostCommands'])) {
                foreach ($this->request->data['Contact']['HostCommands'] as $command_id) {
                    $ext_data_for_changelog['HostCommands'][] = [
                        'id'   => $command_id,
                        'name' => $notification_commands[$command_id],
                    ];
                }
            }
            if (is_array($this->request->data['Contact']['ServiceCommands'])) {
                foreach ($this->request->data['Contact']['ServiceCommands'] as $command_id) {
                    $ext_data_for_changelog['ServiceCommands'][] = [
                        'id'   => $command_id,
                        'name' => $notification_commands[$command_id],
                    ];
                }
            }

            $this->request->data['Contact']['uuid'] = UUID::v4();

            if ($this->Contact->saveAll($this->request->data)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $this->Contact->id,
                    OBJECT_CONTACT,
                    $this->request->data('Container.Container'),
                    $userId,
                    $this->request->data['Contact']['name'],
                    array_merge($ext_data_for_changelog, $this->request->data)
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }

                if ($this->request->ext === 'json') {
                    $this->serializeId();

                    return;
                }
                $this->setFlash(__('<a href="/contacts/edit/%s">Contact</a> successfully saved', $this->Contact->id));
                $this->redirect(['action' => 'index']);
            } else {
                foreach ($this->Customvariable->validationErrors as $customVariableValidationError) {
                    if (isset($customVariableValidationError['name'])) {
                        $this->set('customVariableValidationError', current($customVariableValidationError['name']));
                    }
                }

                foreach ($this->Customvariable->validationErrors as $customVariableValidationError) {
                    if (isset($customVariableValidationError['value'])) {
                        $this->set('customVariableValidationErrorValue', current($customVariableValidationError['value']));
                    }
                }
            }
            if ($this->request->ext === 'json') {
                $this->serializeErrorMessage();

                return;
            }

            if (isset($this->Contact->validationErrors['notify_host_recovery'])) {
                $this->set('validation_host_notification', $this->Contact->validationErrors['notify_host_recovery'][0]);
            }

            if (isset($this->Contact->validationErrors['notify_service_recovery'])) {
                $this->set('validation_service_notification', $this->Contact->validationErrors['notify_service_recovery'][0]);
            }

            $this->setFlash(__('Contact could not be saved'), false);
        }
        $this->set(compact(['containers', '_timeperiods', 'timeperiods', 'notification_commands', 'isLdap', 'Customvariable', '_users']));
        $this->set('_serialize', ['containers', '_timeperiods', 'timeperiods', 'notification_commands']);

    }

    public function addFromLdap() {
        $this->layout = 'angularjs';
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');

        $PHPVersionChecker = new PHPVersionChecker();
        if ($this->request->is('post') || $this->request->is('put')) {
            $samaccountname = str_replace('string:', '', $this->request->data('Ldap.samaccountname'));
            if ($PHPVersionChecker->isVersionGreaterOrEquals7Dot1()) {
                require_once OLD_APP . 'vendor_freedsx_ldap' . DS . 'autoload.php';
                $ldap = new \FreeDSx\Ldap\LdapClient([
                    'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                    'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                    'ssl_allow_self_signed' => true,
                    'ssl_validate_cert'     => false,
                    'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                    'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
                ]);
                if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                    $ldap->startTls();
                }
                $ldap->bind(
                    sprintf(
                        '%s%s',
                        $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                        $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                    ),
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
                );

                $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                    \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                    \FreeDSx\Ldap\Search\Filters::equal('sAMAccountName', $samaccountname)
                );
                if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                    $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                        \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                        \FreeDSx\Ldap\Search\Filters::equal('dn', $samaccountname)
                    );
                }

                $search = FreeDSx\Ldap\Operations::search($filter, 'cn', 'sAMAccountName', 'dn', 'mail');

                /** @var \FreeDSx\Ldap\Entry\Entries $entries */
                $entries = $ldap->search($search);

                foreach ($entries as $entry) {
                    /** @var \FreeDSx\Ldap\Entry\Entries $entry */

                    $entry = $entry->toArray();
                    $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
                    if (isset($entry['uid'])) {
                        $entry['samaccountname'] = $entry['uid'];
                    }
                    $ldapUser = [
                        'mail'           => $entry['mail']['0'],
                        'samaccountname' => $entry['samaccountname'][0]
                    ];
                }

            } else {
                $ldapUser = $this->Ldap->userInfo($samaccountname);
            }
            if (!is_null($ldapUser)) {
                $this->redirect([
                    'controller'     => 'contacts',
                    'action'         => 'add',
                    'ldap'           => 1,
                    'email'          => $ldapUser['mail'],
                    'samaccountname' => $ldapUser['samaccountname'],
                    //Fixing usernames like jon.doe
                    'fix'            => 1 // we need an / behind the username parameter otherwise cakePHP will make strange stuff with a jon.doe username (username with dot ".")
                ]);
            }
            $this->setFlash(__('Contact does not exists in LDAP'), false);
        }

        if ($PHPVersionChecker->isVersionGreaterOrEquals7Dot1()) {
            $usersForSelect = [];
            require_once OLD_APP . 'vendor_freedsx_ldap' . DS . 'autoload.php';
            $ldap = new \FreeDSx\Ldap\LdapClient([
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ]);
            if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                $ldap->startTls();
            }
            $ldap->bind(
                sprintf(
                    '%s%s',
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                ),
                $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
            );

            $filter = \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']);
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $requiredFields = ['uid', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            } else {
                $requiredFields = ['samaccountname', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            }

            $paging = $ldap->paging($search, 100);
            while ($paging->hasEntries()) {
                foreach ($paging->getEntries() as $entry) {
                    $userDn = (string)$entry->getDn();
                    if (empty($userDn)) {
                        continue;
                    }

                    $entry = $entry->toArray();
                    $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
                    foreach ($requiredFields as $requiredField) {
                        if (!isset($entry[$requiredField])) {
                            continue 2;
                        }
                    }

                    if (isset($entry['uid'])) {
                        $entry['samaccountname'] = $entry['uid'];
                    }

                    $displayName = sprintf(
                        '%s, %s (%s)',
                        $entry['givenname'][0],
                        $entry['sn'][0],
                        $entry['samaccountname'][0]
                    );
                    $usersForSelect[$entry['samaccountname'][0]] = $displayName;
                }
                $paging->end();
            }
        } else {
            $usersForSelect = $this->Ldap->findAllUser();
        }

        $usersForSelect = Api::makeItJavaScriptAble($usersForSelect);

        $isPhp7Dot1 = $PHPVersionChecker->isVersionGreaterOrEquals7Dot1();
        $this->set(compact(['usersForSelect', 'systemsettings', 'isPhp7Dot1']));
        $this->set('_serialize', ['usersForSelect', 'isPhp7Dot1']);
    }

    public function loadLdapUserByString() {
        $this->layout = 'blank';

        $usersForSelect = [];
        $samaccountname = $this->request->query('samaccountname');
        if (!empty($samaccountname) && strlen($samaccountname) > 2) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');
            require_once OLD_APP . 'vendor_freedsx_ldap' . DS . 'autoload.php';

            $ldap = new \FreeDSx\Ldap\LdapClient([
                'servers'               => [$systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS']],
                'port'                  => (int)$systemsettings['FRONTEND']['FRONTEND.LDAP.PORT'],
                'ssl_allow_self_signed' => true,
                'ssl_validate_cert'     => false,
                'use_tls'               => (bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS'],
                'base_dn'               => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN'],
            ]);
            if ((bool)$systemsettings['FRONTEND']['FRONTEND.LDAP.USE_TLS']) {
                $ldap->startTls();
            }
            $ldap->bind(
                sprintf(
                    '%s%s',
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.USERNAME'],
                    $systemsettings['FRONTEND']['FRONTEND.LDAP.SUFFIX']
                ),
                $systemsettings['FRONTEND']['FRONTEND.LDAP.PASSWORD']
            );

            $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                \FreeDSx\Ldap\Search\Filters::contains('sAMAccountName', $samaccountname)
            );
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $filter = \FreeDSx\Ldap\Search\Filters::andPHP5(
                    \FreeDSx\Ldap\Search\Filters::raw($systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY']),
                    \FreeDSx\Ldap\Search\Filters::contains('uid', $samaccountname)
                );
            }
            if ($systemsettings['FRONTEND']['FRONTEND.LDAP.TYPE'] === 'openldap') {
                $requiredFields = ['uid', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'uid', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            } else {
                $requiredFields = ['samaccountname', 'mail', 'sn', 'givenname'];
                $search = FreeDSx\Ldap\Operations::search($filter, 'samaccountname', 'mail', 'sn', 'givenname', 'displayname', 'dn');
            }

            $paging = $ldap->paging($search, 100);
            while ($paging->hasEntries()) {
                foreach ($paging->getEntries() as $entry) {
                    $userDn = (string)$entry->getDn();
                    if (empty($userDn)) {
                        continue;
                    }

                    $entry = $entry->toArray();
                    $entry = array_combine(array_map('strtolower', array_keys($entry)), array_values($entry));
                    foreach ($requiredFields as $requiredField) {
                        if (!isset($entry[$requiredField])) {
                            continue 2;
                        }
                    }

                    if (isset($entry['uid'])) {
                        $entry['samaccountname'] = $entry['uid'];
                    }

                    $displayName = sprintf(
                        '%s, %s (%s)',
                        $entry['givenname'][0],
                        $entry['sn'][0],
                        $entry['samaccountname'][0]
                    );
                    $usersForSelect[$entry['samaccountname'][0]] = $displayName;
                }
                $paging->end();
            }
        }

        $usersForSelect = Api::makeItJavaScriptAble($usersForSelect);

        $this->set('usersForSelect', $usersForSelect);
        $this->set('_serialize', ['usersForSelect']);
    }

    /**
     * @param $contact
     * @return bool
     * @deprecated
     * @todo Refactor me with cake4
     */
    protected function __allowDelete($contact) {
        if (is_numeric($contact)) {
            $contactId = $contact;
        } else {
            $contactId = $contact['Contact']['id'];
        }

        $models = [
            '__ContactsToContactgroups',
            '__ContactsToHosttemplates',
            '__ContactsToHosts',
            '__ContactsToServicetemplates',
            '__ContactsToServices',
            '__ContactsToHostescalations',
            '__ContactsToServiceescalations',
        ];
        foreach ($models as $model) {
            $this->loadModel($model);
            $count = $this->{$model}->find('count', [
                'conditions' => [
                    'contact_id' => $contactId,
                ],
            ]);
            if ($count > 0) {
                return false;
            }
        }

        return true;
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

        if (!$ContactsTable->exists($id)) {
            throw new NotFoundException(__('Contact not found'));
        }

        $contact = $ContactsTable->getContactById($id);

        if (!$this->allowedByContainerId(Hash::extract($timeperiod, 'Timeperiod.container_id'))) {
            $this->render403();
            return;
        }

        if (!$this->__allowDelete($timeperiod)) {
            $usedBy = [
                [
                    'baseUrl' => '#',
                    'message' => __('Used by other objects'),
                    'module'  => 'Core'
                ]
            ];

            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting timeperiod'));
            $this->set('usedBy', $usedBy);
            $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
            return;
        }


        $timeperiodEntity = $TimeperiodsTable->get($id);
        if ($TimeperiodsTable->delete($timeperiodEntity)) {
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
            $changelog_data = $this->Changelog->parseDataForChangelog(
                'delete',
                'timeperiods',
                $id,
                OBJECT_TIMEPERIOD,
                [$timeperiod['Timeperiod']['container_id']],
                $User->getId(),
                $timeperiod['Timeperiod']['name'],
                $timeperiod
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

    public function delete($id) {
        if (!$this->Contact->exists($id)) {
            throw new NotFoundException(__('Invalid contact'));
        }
        $userId = $this->Auth->user('id');
        $contact = $this->Contact->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container.id',
                'Container.name'
            ],
            'fields'     => [
                'Contact.id',
                'Contact.name'
            ],
            'conditions' => [
                'Contact.id' => $id
            ]
        ]);
        if (!$this->allowedByContainerId(Hash::extract($contact, 'Container.{n}.id'))) {
            $this->render403();
            return;
        }

        if (!empty(array_diff(Hash::extract($contact['Container'], '{n}.id'), $this->MY_RIGHTS))) {
            $this->render403();
            return;
        }

        if ($this->__allowDelete($id)) {
            if ($this->Contact->delete($id)) {
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    $this->params['action'],
                    $this->params['controller'],
                    $id,
                    OBJECT_CONTACT,
                    Hash::extract($contact['Container'], '{n}.id'),
                    $userId,
                    $contact['Contact']['name'],
                    $contact
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                $this->setFlash(__('Contact deleted'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Could not delete contact'), false);
                $this->redirect(['action' => 'index']);
            }
        } else {
            $contactsCanotDelete[$contact['Contact']['id']] = $contact['Contact']['name'];
            $this->set(compact(['contactsCanotDelete']));
            $this->render('mass_delete');
        }
    }


    public function loadTimeperiods() {
        $this->allowOnlyAjaxRequests();

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $timePeriods = [];
        if (isset($this->request->data['container_ids'])) {
            $containerIds = $ContainersTable->resolveChildrenOfContainerIds($this->request->data['container_ids']);
            $timePeriods = $this->Timeperiod->timeperiodsByContainerId($containerIds, 'list');
            $timePeriods = Api::makeItJavaScriptAble($timePeriods);
        }

        $data = [
            'timeperiods' => $timePeriods,
        ];
        $this->set($data);
        $this->set('_serialize', array_keys($data));
    }

    public function loadUsersByContainerId() {
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

    public function copy($id = null) {
        $userId = $this->Auth->user('id');
        $contacts = $this->Contact->find('all', [
            'recursive'  => 0,
            'contain'    => [
                'Container' => [
                    'fields' => [
                        'Container.id'
                    ],
                ],

                'Customvariable'    => [
                    'fields' => [
                        'name', 'value',
                    ],
                ],
                'HostCommands'      => [
                    'fields' => [
                        'id',
                        'name'
                    ]
                ],
                'ServiceCommands'   => [
                    'fields' => [
                        'id',
                        'name'
                    ]
                ],
                'HostTimeperiod'    => [
                    'fields' => [
                        'HostTimeperiod.id',
                        'HostTimeperiod.name',
                    ]
                ],
                'ServiceTimeperiod' => [
                    'fields' => [
                        'ServiceTimeperiod.id',
                        'ServiceTimeperiod.name',
                    ]
                ]

            ],
            'conditions' => [
                'Contact.id' => func_get_args(),
            ],
        ]);
        $contacts = Hash::combine($contacts, '{n}.Contact.id', '{n}');

        if ($this->request->is('post') || $this->request->is('put')) {

            $datasource = $this->Contact->getDataSource();
            try {
                $datasource->begin();
                foreach ($this->request->data['Contact'] as $sourceContactId => $newContact) {
                    $newContact['uuid'] = UUID::v4();
                    unset($contacts[$sourceContactId]['Contact']['id']); // remove contact id for save
                    $newContactData = [
                        'Contact'        => Hash::merge(
                            $contacts[$sourceContactId]['Contact'],
                            $newContact,
                            [
                                'HostCommands' => [
                                    $contacts[$sourceContactId]['HostCommands'][0]['id']
                                ]
                            ],
                            [
                                'ServiceCommands' => [
                                    $contacts[$sourceContactId]['ServiceCommands'][0]['id']
                                ]
                            ]
                        ),
                        'Customvariable' => Hash::insert(
                            Hash::remove(
                                $contacts[$newContact['source']]['Customvariable'], '{n}.object_id'
                            ),
                            '{n}.objecttype_id',
                            OBJECT_CONTACT
                        ),
                        'Container'      => [
                            'Container' =>
                                Hash::extract($contacts[$sourceContactId]['Container'], '{n}.id')
                        ]
                    ];

                    $this->Contact->create();
                    if (!$this->Contact->saveAll($newContactData)) {
                        $errorMessage = 'Contacts could not be copied.';
                        $errorFields = $this->Contact->invalidFields();

                        if (!empty($errorFields)) {
                            foreach ($errorFields as $errorFieldKey => $errorField) {
                                if (!isset($newContactData['Contact'][$errorFieldKey]) || !isset($errorField[0])) continue;
                                $errorMessage .= '<br />' . $newContactData['Contact'][$errorFieldKey] . ': ' . $errorField[0];
                            }
                        }
                        throw new Exception($errorMessage);
                    }

                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $this->params['action'],
                        $this->params['controller'],
                        $this->Contact->id,
                        OBJECT_CONTACT,
                        Hash::extract($contacts[$sourceContactId]['Container'], '{n}.id'),
                        $userId,
                        $newContact['name'],
                        Hash::merge($contacts[$sourceContactId], ['Contact' => $newContact])
                    );
                    if ($changelog_data) {
                        CakeLog::write('log', serialize($changelog_data));
                    }

                }
                $datasource->commit();
                $this->setFlash(__('Contacts are successfully copied'));
                $this->redirect(['action' => 'index']);
            } catch (Exception $e) {
                $datasource->rollback();
                $this->setFlash(__($e->getMessage()), false);
                $this->redirect(['action' => 'index']);
            }
        }

        $this->set(compact('contacts'));
        $this->set('back_url', $this->referer());
    }

    public function addCustomMacro($counter) {
        $this->allowOnlyAjaxRequests();

        $this->set('objecttype_id', OBJECT_CONTACT);
        $this->set('counter', $counter);
    }

    public function usedBy($id = null) {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if (!$this->Contact->exists($id)) {
            throw new NotFoundException(__('Invalid contact'));
        }

        $this->Contact->bindModel([
            'hasAndBelongsToMany' => [
                'Hosttemplate'      => [
                    'className' => 'Hosttemplate',
                    'joinTable' => 'contacts_to_hosttemplates',
                    'type'      => 'INNER'
                ],
                'Host'              => [
                    'className' => 'Host',
                    'joinTable' => 'contacts_to_hosts',
                    'type'      => 'INNER'
                ],
                'Servicetemplate'   => [
                    'className' => 'Servicetemplate',
                    'joinTable' => 'contacts_to_servicetemplates',
                    'type'      => 'INNER'
                ],
                'Service'           => [
                    'className' => 'Service',
                    'joinTable' => 'contacts_to_services',
                    'type'      => 'INNER'
                ],
                'Hostescalation'    => [
                    'className' => 'Hostescalation',
                    'joinTable' => 'contacts_to_hostescalations',
                    'type'      => 'INNER'
                ],
                'Serviceescalation' => [
                    'className' => 'Serviceescalation',
                    'joinTable' => 'contacts_to_serviceescalations',
                    'type'      => 'INNER'
                ],
                'Contactgroup'      => [
                    'className' => 'Contactgroup',
                    'joinTable' => 'contacts_to_contactgroups',
                    'type'      => 'INNER'
                ]
            ]
        ]);

        $contactWithRelations = $this->Contact->find('first', [
            'recursive'  => -1,
            'contain'    => [
                'Container',
                'Hosttemplate'    => [
                    'fields' => [
                        'Hosttemplate.id',
                        'Hosttemplate.name'
                    ]
                ],
                'Host'            => [
                    'fields' => [
                        'Host.id',
                        'Host.name',
                        'Host.address'
                    ]
                ],
                'Servicetemplate' => [
                    'fields' => [
                        'Servicetemplate.id',
                        'Servicetemplate.name'
                    ]
                ],
                'Service'         => [
                    'fields'          => [
                        'Service.id',
                        'Service.name'
                    ],
                    'Host'            => [
                        'fields' => [
                            'Host.name'
                        ]
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.name'
                        ]
                    ]
                ],
                'Hostescalation.id',
                'Serviceescalation.id',
                'Contactgroup'    => [
                    'Container'
                ]
            ],
            'conditions' => [
                'Contact.id' => $id
            ]
        ]);

        if (!$this->allowedByContainerId(Hash::extract($contactWithRelations, 'Container.{n}.id'))) {
            $this->render403();
            return;
        }

        if (!empty(array_diff(Hash::extract($contactWithRelations['Container'], '{n}.id'), $this->MY_RIGHTS))) {
            $this->render403();
            return;
        }

        /* Format service name for api "hostname|Service oder Service template name" */
        array_walk($contactWithRelations['Service'], function (&$service) {
            $serviceName = $service['name'];
            if (empty($service['name'])) {
                $serviceName = $service['Servicetemplate']['name'];
            }
            $service['name'] = sprintf('%s|%s', $service['Host']['name'], $serviceName);
        });

        array_walk($contactWithRelations['Contactgroup'], function (&$contactgroup) {
            $contactgroup['name'] = sprintf('%s', $contactgroup['Container']['name']);
        });

        //Sort host template, host, service template and service by name
        foreach (['Hosttemplate', 'Host', 'Servicetemplate', 'Service'] as $modelName) {
            $contactWithRelations[$modelName] = Hash::sort($contactWithRelations[$modelName], '{n}.name', 'asc', [
                    'type'       => 'natural',
                    'ignoreCase' => true
                ]
            );
        }

        $this->set(compact(['contactWithRelations']));
        $this->set('_serialize', ['contactWithRelations']);
        $this->set('back_url', $this->referer());
    }
}

