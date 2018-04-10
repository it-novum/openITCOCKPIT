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


/**
 * Acts as the base controller for both the frontend and the backend
 * @package default
 */
App::uses('ServiceResponse', 'Frontend.Lib');
App::uses('Controller', 'Controller');
App::uses('CakeTime', 'Utility');
App::uses('AuthActions', 'Lib');
App::uses('User', 'Model');
App::uses('UUID', 'Lib');

use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;

/**
 * @property User $User
 * @property Changelog $Changelog
 * @property Systemsetting $Systemsetting
 * @property SessionComponent $Session
 * @property FrontendComponent $Frontend
 * @property AppAuthComponent $AppAuth
 * @property CookieComponent $Cookie
 * @property RequestHandlerComponent $RequestHandler
 * @property PaginatorComponent $Paginator
 * @property MenuComponent $Menu
 * @property ConstantsComponent $Constants
 * @property TreeComponent $Tree
 * @property AdditionalLinksComponent $AdditionalLinks
 */
class AppController extends Controller {

    /**
     * @var array
     */
    public $uses = [
        'User',
        'Changelog',
        'Systemsetting',
        'Container',
        'Usergroup',
        'Register',
        'Proxy',
    ];

    private $restEnabledController = [
        'Command',
        'Timeperiod',
        'Contact',
        'Contactgroup',
        'Host',
        'Hostgroup',
        'Service',
        'Servicegroup',
        'Hostescalation',
        'Serviceescalation',
        'Hostdependency',
        'Servicedependency',
        'Container',
        'Tenant',
        'GraphCollection',
        'User',
        'Location',
        'Servicetemplate',
        'Hosttemplate',
        'Servicegroup',
        //'Devicegroup',
        'Servicetemplategroup',
        'Map',
        'Eventcorrelation',
    ];

    /**
     * Used Components
     * @var array
     */
    public $components = [
        'Session',
        'Flash',
        'Frontend.Frontend',
        'Auth' => ['className' => 'AppAuth'],
        'Acl',
        'Cookie',
        'RequestHandler',
        'Paginator',
        'Menu',
        'Constants',
        'Tree',
        'AdditionalLinks',
        //'DebugKit.Toolbar'
    ];

    /**
     * Used View Helpers
     * @var array
     */
    public $helpers = [
        'Html'      => ['className' => 'AppHtml'],
        'Paginator' => ['className' => 'BoostCake.BoostCakePaginator'],
        'Form'      => ['className' => 'AppForm'],
        'Session',
        'Utils',
        'Frontend.Frontend',
        'Auth',
        'AdditionalLinks',
        'Acl',
    ];

    public $hasRootPrivileges = false;
    public $MY_RIGHTS = [];
    public $MY_RIGHTS_LEVEL = [];
    protected $PERMISSIONS = [];
    protected $userLimit = 25;

    /**
     * @var DbBackend
     */
    protected $DbBackend;


    /**
     * Called before every controller actions. Should not be overridden.
     * @return void
     */
    public function beforeFilter() {

        //DANGER ZONE - ALLOW ALL ACTIONS
        //$this->Auth->allow();

        Configure::load('dbbackend');
        $this->DbBackend = new DbBackend(Configure::read('dbbackend'));
        $this->set('DbBackend', $this->DbBackend);

        $this->Auth->authorize = 'Actions';
        //$this->Auth->authorize = 'Controller';
        $this->_beforeAction();
        if (!$this->Auth->loggedIn() && $this->action != 'logout') {
            if ($this->Auth->autoLogin()) {
                $this->__getUserRights();
            }
        } else {
            $this->__getUserRights();
        }

        if (ENVIRONMENT === 'development_test') {
            $autoLoginUserAdmin = $this->User->find('first');
            if (!empty($autoLoginUserAdmin)) {
                $this->Auth->login($autoLoginUserAdmin);
                $this->MY_RIGHTS = [1];
                $this->MY_RIGHTS_LEVEL = [1, 2];
            }
        }
    }

    protected function __getUserRights() {
        //The user is logedIn, so we need to select container permissions out of DB
        $_user = $this->User->findById($this->Auth->user('id'));
        $User = new User($this->Auth);

        $cacheKey = 'userPermissions_' . $User->getId();

        if (!Cache::read($cacheKey, 'permissions')) {
            $rights = [ROOT_CONTAINER];
            $rights_levels = [ROOT_CONTAINER => READ_RIGHT];
            $this->hasRootPrivileges = false;
            $this->MY_RIGHTS = [];
            foreach ($_user['ContainerUserMembership'] as $container) {
                $rights[] = (int)$container['container_id'];
                $rights_levels[(int)$container['container_id']] = $container['permission_level'];

                if ((int)$container['container_id'] === ROOT_CONTAINER) {
                    $rights_levels[ROOT_CONTAINER] = WRITE_RIGHT;
                    $this->hasRootPrivileges = true;
                }

                foreach ($this->Container->children($container['container_id'], false) as $childContainer) {
                    $rights[] = (int)$childContainer['Container']['id'];
                    $rights_levels[(int)$childContainer['Container']['id']] = $container['permission_level'];
                }
            }

            $permissions = $this->Acl->Aro->Permission->find('all', [
                'conditions' => [
                    'Aro.foreign_key' => $this->Auth->user('usergroup_id'),
                ],
                'fields'     => [
                    'Aro.foreign_key',
                    'Permission.aco_id',
                ],
            ]);
            $aros = Hash::combine($permissions, '{n}.Permission.aco_id', '{n}.Permission.aco_id');
            unset($permissions);
            $acos = $this->Acl->Aco->find('threaded', [
                'recursive' => -1,
            ]);
            $permissions = [];
            foreach ($acos as $usergroupAcos) {
                foreach ($usergroupAcos['children'] as $controllerAcos) {
                    $controllerName = strtolower($controllerAcos['Aco']['alias']);
                    if (!strpos($controllerName, 'module')) {
                        //Core
                        foreach ($controllerAcos['children'] as $actionAcos) {
                            //Check if the user group is allowd for $actionAcos action
                            if (!isset($aros[$actionAcos['Aco']['id']])) {
                                continue;
                            }
                            $actionName = strtolower($actionAcos['Aco']['alias']);
                            $permissions[$controllerName][$actionName] = $actionName;
                        }
                    } else {
                        //Plugin / Module
                        $pluginName = Inflector::underscore($controllerName);
                        $pluginAcos = $controllerAcos;
                        foreach ($pluginAcos['children'] as $controllerAcos) {

                            $controllerName = strtolower($controllerAcos['Aco']['alias']);
                            foreach ($controllerAcos['children'] as $actionAcos) {
                                //Check if the user group is allowd for $actionAcos action
                                if (!isset($aros[$actionAcos['Aco']['id']])) {
                                    continue;
                                }
                                $actionName = strtolower($actionAcos['Aco']['alias']);
                                $permissions[$pluginName][$controllerName][$actionName] = $actionName;
                            }
                        }
                    }
                }
            }

            $permissionsForCache = [
                'MY_RIGHTS'         => array_unique($rights),
                'MY_RIGHTS_LEVEL'   => $rights_levels,
                'PERMISSIONS'       => $permissions,
                'hasRootPrivileges' => $this->hasRootPrivileges
            ];
            Cache::write($cacheKey, $permissionsForCache, 'permissions');
        }

        if (!empty($this->Auth->user('paginatorlength'))) {
            $this->Paginator->settings['limit'] = $this->Auth->user('paginatorlength');
            if ($this->Auth->user('paginatorlength') > 100) {
                //paginator maxLimit must be also set now
                $maxLength = $this->Auth->user('paginatorlength');
                if ($this->Auth->user('paginatorlength') > 1000) {
                    $maxLength = 1000;
                }
                $this->Paginator->settings['maxLimit'] = $maxLength;
            }
        } else {
            $this->Paginator->settings['limit'] = 25;
        }

        $permissionsFromCache = Cache::read($cacheKey, 'permissions');
        $this->userLimit = (int)$this->Paginator->settings['limit'];
        $this->MY_RIGHTS = $permissionsFromCache['MY_RIGHTS'];
        $this->MY_RIGHTS_LEVEL = $permissionsFromCache['MY_RIGHTS_LEVEL'];
        $this->PERMISSIONS = $permissionsFromCache['PERMISSIONS'];
        $this->hasRootPrivileges = $permissionsFromCache['hasRootPrivileges'];
        $this->set('hasRootPrivileges', $this->hasRootPrivileges);
        $this->set('aclPermissions', $this->PERMISSIONS);
        $this->set('MY_RIGHTS_LEVEL', $this->MY_RIGHTS_LEVEL);
    }

    /**
     * beforeFilter() Replacement for sub controllers.
     * @return void
     */

    protected function _beforeAction() {
        if (!Cache::read('systemsettings', 'permissions')) {
            Cache::write('systemsettings', $this->Systemsetting->findAsArray(), 'permissions');
        }
        $systemsettings = Cache::read('systemsettings', 'permissions');

        $this->systemname = $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'];

        $this->exportRunningHeaderInfo = false;
        if (isset($systemsettings['FRONTEND']['FRONTEND.SHOW_EXPORT_RUNNING'])) {
            if ($systemsettings['FRONTEND']['FRONTEND.SHOW_EXPORT_RUNNING'] === 'yes') {
                $this->exportRunningHeaderInfo = true;
            }
        }
    }

    /**
     * Action-based authorization callback
     *
     * @param string $user
     *
     * @return bool
     */
    public function isAuthorized($user) {
        return ClassRegistry::getObject('AuthActions')->isAuthorized($user, $this->plugin, $this->name, $this->action);
    }

    /**
     * Called before rendering the main controller view
     * @return void
     */
    public function beforeRender() {
        if (!empty($this->request['paging']) && $this->isApiRequest()) {
            $data = $this->request['paging'];
            $data = current($data);
            $this->set('paging', $data);
        }


        $this->set('exportRunningHeaderInfo', $this->exportRunningHeaderInfo);

        if (isset($this->request->data['Filter']) && $this->request->data['Filter'] !== null) {
            $this->set('isFilter', true);
        } else {
            $this->set('isFilter', false);
        }

        //Add Websocket Information
        if ($this->Auth->loggedIn()) {
            $this->Frontend->setJson('websocket_url', 'wss://' . env('HTTP_HOST') . '/sudo_server');

            if (!Cache::read('systemsettings', 'permissions')) {
                Cache::write('systemsettings', $this->Systemsetting->findAsArray(), 'permissions');
            }
            $systemsettings = Cache::read('systemsettings', 'permissions');

            $this->Frontend->setJson('akey', $systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY']);

        }


        ClassRegistry::addObject('AuthComponent', $this->Auth);
        $this->set('sideMenuClosed', isset($_COOKIE['sideMenuClosed']) && $_COOKIE['sideMenuClosed'] == 'true');
        $this->set('loggedIn', $this->Auth->loggedIn());
        $this->set('systemname', $this->systemname);
        //$this->set('systemTimezone', $this->systemTimezone); done with ini_get('date.timezone')

        if (!$this->isApiRequest() && !$this->isAngularJsRequest()) {
            $menu = $this->Menu->compileMenu();
            $menu = $this->Menu->filterMenuByAcl($menu, $this->PERMISSIONS);
            $this->set('menu', $menu);
        }


        /*
         * This is the fix for HABTM validation
         * Thanks to: http://bakery.cakephp.org/articles/kogalex/2010/01/13/quick-fix-for-habtm-validation
         */
        $model = Inflector::singularize($this->name);
        if (isset($this->{$model}->hasAndBelongsToMany)) {
            foreach ($this->{$model}->hasAndBelongsToMany as $k => $v) {
                if (isset($this->{$model}->validationErrors[$k])) {
                    $this->{$model}->{$k}->validationErrors[$k] = $this->{$model}->validationErrors[$k];
                }
            }
        }

        if (!$this->isApiRequest() && !$this->isAngularJsRequest()) {
            // @FIXME: ComponentCollection::beforeRender is triggered before Controller::beforeRender
            // which has the effect that passing data to the frontend from Controller::beforeRender
            // won't work.
            $this->Frontend->beforeRender($this);
        }

        if (class_exists('ConnectionManager') && Configure::read('debug') == 2) {
            $noLogs = !isset($sqlLogs);
            if ($noLogs):
                $sources = ConnectionManager::sourceList();

                $sqlLogs = [];
                foreach ($sources as $source):
                    $db = ConnectionManager::getDataSource($source);
                    if (!method_exists($db, 'getLog')):
                        continue;
                    endif;
                    $sqlLogs[$source] = $db->getLog();
                endforeach;
            endif;
            $queryLog = LOGS . 'query.log';
            $logfile = fopen($queryLog, 'a+');

            foreach ($sqlLogs as $datasource => $log) {
                fwrite($logfile, sprintf(
                    '******** DS "%s" %s queries took %s ms ********%s',
                    $datasource,
                    $log['count'],
                    $log['time'],
                    PHP_EOL
                ));
                foreach ($log['log'] as $query) {
                    fwrite($logfile, sprintf(
                        '-- [%s] Affected %s, num. Rows %s, %s ms%s',
                        date('H:i:s'),
                        $query['affected'],
                        $query['numRows'],
                        $query['took'],
                        PHP_EOL
                    ));
                    fwrite($logfile, sprintf(
                        '%s%s-- Params:%s%s',
                        $query['query'],
                        PHP_EOL,
                        implode(',', $query['params']),
                        PHP_EOL
                    ));
                }
            }
            fwrite($logfile, sprintf(
                '--------------------------------%s%s',
                PHP_EOL,
                PHP_EOL
            ));

            fclose($logfile);
        }


        parent::beforeRender();
    }

    /**
     * Proxy for Controller::redirect() to handle AJAX redirects
     *
     * @param mixed $url
     * @param int $status
     * @param bool $exit
     *
     * @return void
     */
    public function redirect($url, $status = null, $exit = true) {
        // this statement catches not authenticated or not authorized ajax requests
        // AuthComponent will call Controller::redirect(null, 403) in those cases.
        // with this we're making sure that we return valid JSON responses in all cases
        if ($this->request->is('ajax') && $url == null && $status == 403) {
            $this->response = new ServiceResponse(Types::CODE_NOT_AUTHENTICATED);
            $this->response->send();
            $this->_stop();

            return;
        }

        parent::redirect($url, $status, $exit);

        return;
    }

    /**
     * Sets a flash messages and redirects to the given url.
     * Will use the index action, if no $url was given.
     *
     * @param string $msg
     * @param array|string $url
     *
     * @return void
     */
    public function flashBack($msg, $url = null, $success = false) {
        if (!$url) {
            $url = ['action' => 'index'];
        }
        $this->setFlash($msg, $success);
        $this->redirect($url);
    }

    /**
     * Override setFlash to add bootstrap classes
     *
     * @param string $message
     * @param bool|string $success
     * @param string $key
     * @param boolean $autoHide
     *
     * @return void
     */
    public function setFlash($message, $success = true, $key = 'flash', $autoHide = true) {
        $successClass = $success;
        if ($success === true) {
            $successClass = 'success';
        }
        if ($success === false) {
            $successClass = 'danger';
        }

        $this->Flash->set($message, [
            'element' => 'default',
            'params'  => [
                'class' => 'alert ' . ($autoHide ? 'auto-hide' : '') . ' alert-' . $successClass
            ]
        ]);
    }

    /**
     * Creates a service response for the webservices. Prepares the data
     * and renders the corresponding view. Should be used in return statements,
     * i. e.:
     * return $this->serviceResponse(Types::CODE_SUCCESS, array('message' => 'all good'));
     *
     * @param string $code the return code of the action
     * @param array $data array for the data key
     *
     * @return ServiceResponse
     */
    public function serviceResponse($code, $data = []) {
        return new ServiceResponse($code, $data);
    }

    /**
     * Responds in the widget response format.
     *
     * @param    string $html The action HTML
     *
     * @return    string    The rendered HTML
     */
    protected function widgetResponse(CakeResponse $response) {
        // get the frontendData set by the Frontend plugin and remove unnecessary data
        $frontendData = $this->viewVars['frontendData'];
        unset($frontendData['Types']);
        $response = [
            'code' => Types::CODE_SUCCESS,
            'data' => [
                'frontendData' => $frontendData,
                'html'         => $response->body(),
            ],
        ];

        return new ServiceResponse($response);
    }

    /**
     * Instantiates the correct view class, hands it its data, and uses it to render the view output.
     *
     * @param string $action Action name to render
     * @param string $layout Layout to use
     * @param string $file File to use for rendering
     *
     * @return string Full output string of view contents
     */
    public function render($action = null, $layout = null, $file = null) {
        // if this is a widget request, we use widgetResponse to guarantee a
        // consistent data format
        if (isset($this->request->params['widget']) && $this->request->params['widget'] === true) {
            if ($layout === null) {
                $layout = 'plain';
            }
            $response = parent::render($action, $layout, $file);

            return $this->widgetResponse($response);
        }

        return parent::render($action, $layout, $file);
    }

    /**
     * Unbind all accociations for the next find() call for every model
     *
     * @param  String $ModelName The Name of the Model, you want to unbind all accociations
     *
     * @return void
     * @since 3.0
     */
    protected function __unbindAssociations($ModelName) {
        foreach (['hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany'] as $association) {
            if (!empty($this->{$ModelName}->{$association})) {
                foreach ($this->{$ModelName}->{$association} as $accociatedModel) {
                    $this->{$ModelName}->unbindModel([$association => [$accociatedModel['className']]]);
                }
            }
        }
    }

    /**
     * Dispatches the controller action. Checks that the action
     * exists and isn't private.
     * This override is a custom addition to make the 'additional links' more convenient.
     *
     * @param CakeRequest $request
     *
     * @return mixed The resulting response.
     * @throws PrivateActionException When actions are not public or prefixed by _
     * @throws MissingActionException When actions are not defined and scaffolding is
     *    not enabled.
     */
    public function invokeAction(CakeRequest $request) {
        if ($this->isApiRequest() || $this->isAngularJsRequest()) {
            return parent::invokeAction($request);
        }
        $result = parent::invokeAction($request);

        // Set the additional links for each controller!
        $controller = Inflector::tableize($this->name);
        $action = $request->params['action'];
        $positions = ['top', 'list', 'bottom', 'tab'];
        $contentPositions = ['top', 'center', 'bottom', 'form'];

        $additionalLinks = $this->AdditionalLinks->fetchLinkData($controller, $action, $positions);
        $additionalContent = $this->AdditionalLinks->fetchContentData($controller, $action, $contentPositions);
        //debug($additionalContent);
        foreach ($additionalLinks as $viewPosition => $linkData) {
            $this->set('additionalLinks' . ucfirst($viewPosition), $linkData);
            if (!empty($linkData) && $viewPosition == 'tab') {
                foreach ($linkData as $key => $data) {
                    //add an id so we can identify tabs
                    $linkData[$key]['uuid'] = UUID::v4();
                }
            }
            //defines the vars link $additionalLinkList or $additionalLinkTab
            $this->set('additionalLinks' . ucfirst($viewPosition), $linkData);

        }

        foreach ($additionalContent as $viewPosition => $linkData) {
            //defines the vars link $additionalElementsList or $additionalElementsTab
            $this->set('additionalElements' . ucfirst($viewPosition), $linkData);
        }

        return $result; // Return what was returned before

    }

    /**
     * @throws MethodNotAllowedException
     */
    protected function allowOnlyAjaxRequests() {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException(__('This is only allowed via AJAX.'));
        }
    }

    /**
     * @throws MethodNotAllowedException
     */
    protected function allowOnlyPostRequests() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException(__('This is only allowed via POST.'));
        }
    }

    public function getNamedParameter($paramName, $default = null) {
        if (isset($this->request->params['named'][$paramName])) {
            return $this->request->params['named'][$paramName];
        }

        return $default;
    }

    /**
     * REST API functionality
     */
    protected function serializeId() {
        if ($this->request->ext != 'json') {
            return;
        }
        $name = Inflector::singularize($this->name);
        $this->set('id', $this->{$name}->id);

        $serializeVariableNames = ['id'];
        switch ($name) {
            case 'Command':
                $this->set('command_arguments', $this->Commandargument->getLastInsertedDataWithId());
                $serializeVariableNames[] = 'command_argument_ids';
                $serializeVariableNames[] = 'command_arguments';
                break;
            case 'Tenant':
            case 'Location':
                $this->set('container_id', $this->Container->id);
                $serializeVariableNames[] = 'container_id';
                break;
        }

        $this->set('_serialize', $serializeVariableNames);
    }

    /**
     * REST API functionality
     */
    protected function serializeErrorMessage() {
        if ($this->isAngularJsRequest()) {
            $this->response->statusCode(400);
        }
        $name = Inflector::singularize($this->name);
        $error = $this->{$name}->validationErrors;
        $this->set(compact('error'));
        $this->set('_serialize', ['error']);
    }

    ///**
    // * REST API functionality
    // * @param int $id
    // */
    //public function view($id){
    //	$name = Inflector::singularize($this->name);
    //	if(in_array($name, $this->restEnabledController)){
    //		$record = $this->{$name}->findById($id);
    //		$this->set($record);
    //		$this->set('_serialize', array_keys($record));
    //	}
    //}

    //$useLevel === false: Check if user is permitted to SEE this object
    //$useLevel === true:  Check if user is permitted to EDIT this object
    public function allowedByContainerId($containerIds = [], $useLevel = true) {
        if ($this->hasRootPrivileges === true) {
            return true;
        }

        if ($useLevel === true) {
            $MY_WRITE_RIGHTS = array_filter($this->MY_RIGHTS_LEVEL, function ($value) {
                if ((int)$value === WRITE_RIGHT) {
                    return true;
                }

                return false;
            });
            $MY_WRITE_RIGHTS = array_keys($MY_WRITE_RIGHTS);
            if (!is_array($containerIds)) {
                $containerIds = [$containerIds];
            }
            $result = array_intersect($containerIds, $MY_WRITE_RIGHTS);
            if (!empty($result)) {
                return true;
            }

            if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
                throw new ForbiddenException('403 Forbidden');
            }

            return false;
        }

        $rights = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);

        if (is_array($containerIds)) {
            $result = array_intersect($containerIds, $rights);
            if (!empty($result)) {
                return true;
            }
        } else {
            if (in_array($containerIds, $rights)) {
                return true;
            }
        }

        if ($this->isApiRequest()) {
            throw new ForbiddenException('404 Forbidden');
        }

        return false;
    }

    protected function getWriteContainers() {
        $MY_WRITE_RIGHTS = array_filter($this->MY_RIGHTS_LEVEL, function ($value) {
            if ((int)$value === WRITE_RIGHT) {
                return true;
            }

            return false;
        });
        $MY_WRITE_RIGHTS = array_keys($MY_WRITE_RIGHTS);

        return $MY_WRITE_RIGHTS;
    }

    protected function hasPermission($action = null, $controller = null, $plugin = null) {
        //return false;
        if ($plugin === null) {
            $plugin = Inflector::classify($this->params['plugin']);
        }

        if ($plugin === null || $plugin === '') {
            return isset($this->PERMISSIONS[$controller][$action]);
        }

        return isset($this->PERMISSIONS[$plugin][$controller][$action]);
    }

    /**
     * @param $containerId
     * @return bool
     */
    protected function isWritableContainer($containerId){
        if($this->hasRootPrivileges === true){
            return true;
        }
        if(isset($this->MY_RIGHTS_LEVEL[$containerId])){
            return (int)$this->MY_RIGHTS_LEVEL[$containerId] === WRITE_RIGHT;
        }
        return false;
    }

    public function render403($options = []) {
        $_options = [
            'headline' => __('Permission denied'),
            'error'    => __('You are not permitted to access this object'),
            'icon'     => 'fa-exclamation-triangle',
            'referer'  => ['action' => 'index'],
        ];

        $options = Hash::merge($_options, $options);

        $this->set('options', $options);
        $this->render('/Errors/error403');
    }

    /**
     * @return Model[]
     */
    protected function getLoadedModels() {
        $models = [];
        foreach ($this->uses as $modelName) {
            if (strpos($modelName, '.') !== false) {
                list($plugin, $modelName) = explode('.', $modelName);
            }
            $models[$modelName] = $this->{$modelName};
        }

        return $models;
    }

    protected function isApiRequest() {
        if ($this->isJsonRequest() || $this->isXmlRequest()) {
            return true;
        }

        return false;
    }

    protected function isAngularJsRequest() {
        if ($this->isApiRequest()) {
            return isset($this->request->query['angular']);
        }
        return false;
    }

    protected function isJsonRequest() {
        return $this->request->ext === 'json';
    }

    protected function isXmlRequest() {
        return $this->request->ext === 'xml';
    }

    /**
     * @param array $usedBy
     * @param string $type
     * @return array
     */
    protected function getUsedByForFrontend($usedBy = [], $type = 'host') {
        $result = [];
        $action = 'hostUsedBy';
        if ($type === 'service') {
            $action = 'serviceUsedBy';
        }

        foreach ($usedBy as $moduleName => $moduleId) {
            switch ($moduleId) {
                case AUTOREPORT_MODULE:
                    $result[] = [
                        'baseUrl' => Router::url([
                                'controller' => 'autoreports',
                                'action'     => $action,
                                'plugin'     => 'autoreport_module',
                            ]) . '/',
                        'message' => __('Used by Autoreport module'),
                        'module'  => 'AutoreportModule'
                    ];
                    break;
                case EVENTCORRELATION_MODULE:
                    $result[] = [
                        'baseUrl' => Router::url([
                                'controller' => 'eventcorrelations',
                                'action'     => $action,
                                'plugin'     => 'eventcorrelation_module',
                            ]) . '/',
                        'message' => __('Used by Eventcorrelation module'),
                        'module'  => 'EventcorrelationModule'
                    ];
                    break;
                default:
                    throw new NotImplementedException('Module not implemented yet');
                    break;
            }
        }
        return $result;
    }


    public function checkForUpdates() {
        $path = APP . 'Lib' . DS . 'AvailableVersion.php';
        $availableVersion = '???';
        if (file_exists($path)) {
            require_once $path;
            $availableVersion = openITCOCKPIT_AvailableVersion::get();
        }

        Configure::load('version');

        $this->set([
            'availableVersion' => $availableVersion,
            'installedVersion' => Configure::read('version'),
        ]);
    }
}

