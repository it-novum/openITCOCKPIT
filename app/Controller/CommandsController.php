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
use App\Model\Table\MacrosTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CommandsFilter;

class CommandsController extends AppController {
    public $uses = ['Command', 'Commandargument'];
    public $layout = 'Admin.default';


    public function index() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        $CommandFilter = new CommandsFilter($this->request);

        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $CommandFilter->getPage());
        $all_commands = $Commands->getCommandsIndex($CommandFilter, $PaginateOMat);

        $this->set('all_commands', $all_commands);
        $toJson = ['all_commands', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_commands', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }


    public function view($id = null) {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var CommandsTable $Commands */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        if (!$Commands->exists($id)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $command = $Commands->getCommandById($id);
        $this->set('command', $command);
        $this->set('_serialize', ['command']);
    }

    public function add() {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $command = $Commands->newEntity();

            $command = $Commands->patchEntity($command, $this->request->data('Command'));
            $command->set('uuid', UUID::v4());

            $Commands->save($command);
            if ($command->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $command->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors
                $userId = $this->Auth->user('id');
                $requestData = $this->request->data;
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'add',
                    $this->params['controller'],
                    $command->get('id'),
                    OBJECT_COMMAND,
                    [ROOT_CONTAINER],
                    $userId,
                    $requestData['Command']['name'],
                    $requestData
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                if ($this->request->ext == 'json') {
                    $this->serializeId(); // REST API ID serialization
                    return;
                }
            }
            $this->set('command', $command);
            $this->set('_serialize', ['command']);
        }
    }

    public function edit($id = null) {
        $this->layout = 'angularjs';
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        if (!$Commands->exists($id)) {
            throw new NotFoundException('Command not found');
        }
        $command = $Commands->get($id, [
            'contain' => 'commandarguments'
        ]);
        $commandForChangeLog = $command;

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $command = $Commands->patchEntity($command, $this->request->data('Command'));
            $Commands->save($command);
            if ($command->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $command->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors
                $userId = $this->Auth->user('id');
                $requestData = $this->request->data;
                $changelog_data = $this->Changelog->parseDataForChangelog(
                    'edit',
                    $this->params['controller'],
                    $command->get('id'),
                    OBJECT_COMMAND,
                    [ROOT_CONTAINER],
                    $userId,
                    $requestData['Command']['name'],
                    $requestData,
                    $commandForChangeLog
                );
                if ($changelog_data) {
                    CakeLog::write('log', serialize($changelog_data));
                }
                if ($this->request->ext == 'json') {
                    $this->serializeId(); // REST API ID serialization
                    return;
                }
            }
        }

        $this->set('command', $command);
        $this->set('_serialize', ['command']);
    }

    public function delete($id = null) {
        $this->layout = 'angularjs';


        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var CommandsTable $Commands */
        $Commands = TableRegistry::getTableLocator()->get('Commands');
        if (!$Commands->exists($id)) {
            throw new NotFoundException(__('Invalid command'));
        }

        $command = $Commands->getCommandById($id);
        if (!$this->__allowDelete($command)) {
            $usedBy = [
                [
                    'baseUrl' => Router::url([
                            'controller' => 'commands',
                            'action'     => 'usedBy',
                            'plugin'     => '',
                        ]) . '/',
                    'message' => __('Used by other objects'),
                    'module'  => 'Core'
                ]
            ];

            $this->response->statusCode(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->set('message', __('Issue while deleting command'));
            $this->set('usedBy', $usedBy);
            $this->set('_serialize', ['success', 'id', 'message', 'usedBy']);
            return;
        }


        if ($Commands->delete($Commands->get($id))) {
            $userId = $this->Auth->user('id');
            $changelog_data = $this->Changelog->parseDataForChangelog(
                $this->params['action'],
                $this->params['controller'],
                $id,
                OBJECT_COMMAND,
                [ROOT_CONTAINER],
                $userId,
                $command['Command']['name'],
                $command
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

    protected function __allowDelete($command) {
        //Check if the command is used somewere, if yes we can not delete it!
        $this->loadModel('__ContactsToServicecommands');
        $contactCount = $this->__ContactsToServicecommands->find('count', [
            'recursive'  => -1,
            'conditions' => [
                '__ContactsToServicecommands.command_id' => $command['Command']['id'],
            ],
        ]);
        if ($contactCount > 0) {
            return false;
        }

        $this->loadModel('__ContactsToHostcommands');
        $contactCount = $this->__ContactsToHostcommands->find('count', [
            'recursive'  => -1,
            'conditions' => [
                '__ContactsToHostcommands.command_id' => $command['Command']['id'],
            ],
        ]);
        if ($contactCount > 0) {
            return false;
        }

        $this->loadModel('Hosttemplate');
        $hostCount = $this->Hosttemplate->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'Hosttemplate.command_id' => $command['Command']['id'],
            ],
        ]);
        if ($hostCount > 0) {
            return false;
        }

        $this->loadModel('Servicetemplate');
        $serviceCount = $this->Servicetemplate->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'Servicetemplate.command_id' => $command['Command']['id'],
            ],
        ]);
        if ($serviceCount > 0) {
            return false;
        }

        $this->loadModel('Host');
        $hostCount = $this->Host->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'Host.command_id' => $command['Command']['id'],
            ],
        ]);
        if ($hostCount > 0) {
            return false;
        }

        $this->loadModel('Service');
        $serviceCount = $this->Service->find('count', [
            'recursive'  => -1,
            'conditions' => [
                'Service.command_id' => $command['Command']['id'],
            ],
        ]);
        if ($serviceCount > 0) {
            return false;
        }

        return true;
    }

    public function addCommandArg($id = null) {
        $this->allowOnlyAjaxRequests();

        //Fetching arguments out of $_POST or the database
        if (!empty($this->request->data)) {
            $all_arguments = $this->request->data;
        } else if ($id !== null) {
            $all_arguments = $this->Commandargument->find('list', [
                'conditions' => [
                    'command_id' => $this->Command->findById($id)['Command']['id'],
                ],
            ]);
        } else {
            $all_arguments = [];
        }

        $argumentsCount = 1;

        while (in_array('$ARG' . $argumentsCount . '$', $all_arguments)) {
            $argumentsCount++;
        }

        $newArgument = '$ARG' . $argumentsCount . '$';
        $this->set(compact(['newArgument', 'argumentsCount', 'id']));
    }

    public function loadMacros() {
        /** @var $Macro MacrosTable */
        $Macro = TableRegistry::getTableLocator()->get('Macros');
        $all_macros = $Macro->getAllMacrosInCake2Format();


        //Sorting the SQL result in a human frindly way. Will sort $USER10$ below $USER2$
        $all_macros = Hash::sort($all_macros, '{n}.Macro.name', 'asc', 'natural');

        $this->set('all_macros', $all_macros);
    }

    private function getCommandTypes() {
        return [
            CHECK_COMMAND        => __('Service check command'),
            HOSTCHECK_COMMAND    => __('Host check command'),
            NOTIFICATION_COMMAND => __('Notification command'),
            EVENTHANDLER_COMMAND => __('Eventhandler command'),
        ];
    }

    private function rewritePostData() {
        $requestData = $this->request->data;
        // See MacrosController.php function _rewritePostData() for more information about this
        $Commandarguments = [];
        if (isset($this->request->data['Commandargument'])) {
            $Commandarguments = $this->request->data['Commandargument'];
            $requestData['Commandargument'] = [];
        }
        foreach ($Commandarguments as $data) {
            // Remove empty values, because nagios will throw a config error
            if (!isset($data['name']) || strlen($data['name']) == 0 || !isset($data['human_name']) || strlen($data['human_name']) == 0) {
                continue;
            }
            $requestData['Commandargument'][] = $data;
        }

        return $requestData;
    }


    public function getConsoleWelcome() {
        $welcomeMessage = "This is a terminal connected to your " . $this->systemname . " " .
            "Server, this is very powerful to test and debug plugins.\n" .
            "User: \033[31mnagios\033[0m\nPWD: \033[35m/opt/openitc/nagios/libexec/\033[0m\n\n";

        $this->set('welcomeMessage', $welcomeMessage);
        $this->set('_serialize', ['welcomeMessage']);

    }

    //ALC permission
    public function terminal() {
        return null;
    }

    public function usedBy($id = null) {
        if (!$this->Command->exists($id)) {
            throw new NotFoundException(__('Invalid servicetemplate'));
        }

        $command = $this->Command->findById($id);
        $commandName = $command['Command']['name'];


        $this->loadModel('Servicetemplate');
        $servicestemplates = $this->Servicetemplate->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'Servicetemplate.container_id' => $this->MY_RIGHTS,
                'Servicetemplate.command_id'   => $command['Command']['id'],
            ],
            'fields'     => [
                'Servicetemplate.id', 'Servicetemplate.description', 'Servicetemplate.name',
            ],
            'order'      => [
                'Servicetemplate.name' => 'asc',
            ],
        ]);

        $this->set(compact(['servicestemplates', 'commandName']));
        $this->set('back_url', $this->referer());
    }

    public function copy($id = null) {
        $this->layout = 'angularjs';

        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var $Commands CommandsTable */
        $Commands = TableRegistry::getTableLocator()->get('Commands');

        if ($this->request->is('get')) {
            $commands = $Commands->getCommandsForCopy(func_get_args());
            $this->set('commands', $commands);
            $this->set('_serialize', ['commands']);
            return;
        }

        $hasErrors = false;

        if ($this->request->is('post')) {
            $userId = $this->Auth->user('id');
            $Cache = new KeyValueStore();

            $postData = $this->request->data('data');

            foreach ($postData as $index => $commandData) {
                if (!isset($commandData['Command']['id'])) {
                    //Create/clone command
                    $sourceCommandId = $commandData['Source']['id'];
                    if (!$Cache->has($sourceCommandId)) {
                        $sourceCommand = $Commands->get($sourceCommandId, [
                            'contain' => [
                                'Commandarguments'
                            ]
                        ])->toArray();
                        $Cache->set($sourceCommand['id'], $sourceCommand);
                    }

                    $sourceCommand = $Cache->get($sourceCommandId);

                    $newCommandData = [
                        'name'             => $commandData['Command']['name'],
                        'command_line'     => $commandData['Command']['command_line'],
                        'command_type'     => $sourceCommand['command_type'],
                        'description'      => $commandData['Command']['description'],
                        'uuid'             => UUID::v4(),
                        'commandarguments' => $sourceCommand['commandarguments']
                    ];

                    $newCommandEntity = $Commands->newEntity($newCommandData);
                }

                $action = 'copy';
                if (isset($commandData['Command']['id'])) {
                    //Update existing command
                    //This happens, if a user copy multiple commands, and one run into an validation error
                    //All commands without validation errors got already saved to the database
                    $newCommandEntity = $Commands->get($commandData['Command']['id']);
                    $newCommandEntity = $Commands->patchEntity($newCommandEntity, $commandData['Command']);
                    $action = 'edit';
                }
                $Commands->save($newCommandEntity);

                $postData[$index]['Error'] = [];
                if ($newCommandEntity->hasErrors()) {
                    $hasErrors = true;
                    $postData[$index]['Error'] = $newCommandEntity->getErrors();
                } else {
                    //No errors
                    $postData[$index]['Command']['id'] = $newCommandEntity->get('id');

                    $userId = $this->Auth->user('id');
                    $changelog_data = $this->Changelog->parseDataForChangelog(
                        $action,
                        $this->params['controller'],
                        $postData[$index]['Command']['id'],
                        OBJECT_COMMAND,
                        [ROOT_CONTAINER],
                        $userId,
                        $postData[$index]['Command']['name'],
                        ['Command' => $newCommandData]
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
}

