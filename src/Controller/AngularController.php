<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

declare(strict_types=1);

namespace App\Controller;

use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\openITCOCKPIT_AvailableVersion;
use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use App\Model\Table\DocumentationsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Cache\Cache;
use Cake\Http\CallbackStream;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use DateTime;
use DateTimeZone;
use Exception;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\Menu\Menu;
use itnovum\openITCOCKPIT\Core\ServiceMacroReplacer;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\System\Gearman;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\HostAndServiceSummaryIcon;
use itnovum\openITCOCKPIT\Core\Views\PieChart;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;
use RuntimeException;

/**
 * Class AngularController
 * @package App\Controller
 */
class AngularController extends AppController {

    private $state = 'unknown';

    private $errorCount = 0;

    public function paginator() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function scroll() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function mass_delete() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function confirm_delete() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function confirm_deactivate() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function mass_activate() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function mass_deactivate() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function export() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function popover_graph(){
        //Return HTML Template for PaginatorDirective
        return;
    }

    /**
     * @throws Exception
     */
    public function user_timezone() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $session = $this->request->getSession();
        $session->close();

        $user = $this->getUser();
        $userTimezone = $user->get('timezone');
        if (strlen($userTimezone) < 2) {
            $userTimezone = 'Europe/Berlin';
        }
        $UserTime = new DateTime($userTimezone);
        $ServerTime = new DateTime();
        $ServerTimeZone = new DateTimeZone($ServerTime->getTimezone()->getName());
        $timezone = [
            'user_timezone'              => $userTimezone,
            'user_time_to_server_offset' => $this->get_timezone_offset($ServerTimeZone->getName(), $userTimezone),
            'user_offset'                => $UserTime->getOffset(),
            'server_time_utc'            => time(),
            'server_time'                => date('F d, Y H:i:s'),
            'server_timezone_offset'     => $ServerTime->getOffset()
        ];
        $this->set('timezone', $timezone);
        $this->viewBuilder()->setOption('serialize', ['timezone']);
    }

    /**
     * @param $remote_tz
     * @param null $origin_tz
     * @return bool|int
     * @throws Exception
     */
    private function get_timezone_offset($remote_tz, $origin_tz = null) {
        if ($origin_tz === null) {
            if (!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        $origin_dtz = new DateTimeZone($origin_tz);
        $remote_dtz = new DateTimeZone($remote_tz);
        $origin_dt = new DateTime("now", $origin_dtz);
        $remote_dt = new DateTime("now", $remote_dtz);
        $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
        return $offset;
    }

    public function version_check() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $path = APP . 'Lib' . DS . 'openITCOCKPIT_AvailableVersion.php';
        $availableVersion = '???';
        if (file_exists($path)) {
            $availableVersion = openITCOCKPIT_AvailableVersion::get();
        }
        $newVersionAvailable = false;
        if (version_compare($availableVersion, OPENITCOCKPIT_VERSION) > 0 && $this->hasRootPrivileges) {
            $newVersionAvailable = true;
        }

        $this->set('newVersionAvailable', $newVersionAvailable);
        $this->viewBuilder()->setOption('serialize', ['newVersionAvailable']);
    }

    public function menustats() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }


        $user = $this->getUser();
        $showstatsinmenu = (bool)$user->get('showstatsinmenu');

        $hoststatusCount = [
            '1' => 0,
            '2' => 0,
        ];
        $servicestatusCount = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
        ];

        if ($showstatsinmenu) {

            $MY_RIGHTS = [];
            if($this->hasRootPrivileges === false){
                $MY_RIGHTS = $this->MY_RIGHTS;
            }

            if ($this->DbBackend->isNdoUtils()) {
                /** @var HostsTable $HostsTable */
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

                $hoststatusCount = $HostsTable->getHoststatusCount($MY_RIGHTS, false);
                $servicestatusCount = $HostsTable->getServicestatusCount($MY_RIGHTS, false);
            }

            if ($this->DbBackend->isCrateDb()) {
                throw new MissingDbBackendException('MissingDbBackendException');

            }

            if ($this->DbBackend->isStatusengine3()) {
                /** @var HostsTable $HostsTable */
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

                $hoststatusCount = $HostsTable->getHoststatusCountStatusengine3($MY_RIGHTS, false);
                $servicestatusCount = $HostsTable->getServicestatusCountStatusengine3($MY_RIGHTS, false);
            }

        }
        $this->set(compact(['showstatsinmenu', 'hoststatusCount', 'servicestatusCount']));
        $this->viewBuilder()->setOption('serialize', ['showstatsinmenu', 'hoststatusCount', 'servicestatusCount']);
    }

    public function statuscount() {
        if (!$this->isApiRequest()) {
            throw new RuntimeException('Only for API requests');
        }
        $session = $this->request->getSession();
        $session->close();

        $recursive = false;
        if ($this->request->getQuery('recursive') === 'true') {
            $recursive = true;
        }

        $containerIds = $this->request->getQuery('containerIds', [ROOT_CONTAINER]);
        if (!is_numeric($containerIds) && !is_array($containerIds)) {
            $containerIds = ROOT_CONTAINER;
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($recursive) {
            //get recursive container ids
            $containerIdToResolve = $containerIds;
            $children = $ContainersTable->getChildren($containerIdToResolve[0]);
            $containerIdsResolved = Hash::extract($children, '{n}.id');
            $recursiveContainerIds = [];
            foreach ($containerIdsResolved as $containerId) {
                if (in_array($containerId, $this->MY_RIGHTS)) {
                    $recursiveContainerIds[] = $containerId;
                }
            }
            $containerIds = array_merge($containerIds, $recursiveContainerIds);
        }

        //We need integers for cratedb
        $containerIdsForQuery = [];
        foreach ($containerIds as $containerId) {
            $containerIdsForQuery[] = (int)$containerId;
        }

        $hoststatusCount = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
        ];

        $servicestatusCount = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
        ];


        if ($this->DbBackend->isNdoUtils()) {
            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            $hoststatusCount = $HostsTable->getHoststatusCount($containerIdsForQuery, true);
            $servicestatusCount = $HostsTable->getServicestatusCount($containerIdsForQuery, true);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->DbBackend->isStatusengine3()) {
            /** @var HostsTable $HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

            $hoststatusCount = $HostsTable->getHoststatusCountStatusengine3($containerIdsForQuery, true);
            $servicestatusCount = $HostsTable->getServicestatusCountStatusengine3($containerIdsForQuery, true);
        }

        $hoststatusSum = array_sum($hoststatusCount);
        $servicestatusSum = array_sum($servicestatusCount);

        $hoststatusCountPercentage = [];
        $servicestatusCountPercentage = [];
        foreach ($hoststatusCount as $stateId => $count) {
            if ($hoststatusSum > 0) {
                $hoststatusCountPercentage[$stateId] = round($count / $hoststatusSum * 100, 2);
            } else {
                $hoststatusCountPercentage[$stateId] = 0;
            }
        }

        foreach ($servicestatusCount as $stateId => $count) {
            if ($servicestatusSum > 0) {
                $servicestatusCountPercentage[$stateId] = round($count / $servicestatusSum * 100, 2);
            } else {
                $servicestatusCountPercentage[$stateId] = 0;
            }
        }


        $this->set(compact([
            'hoststatusCount',
            'servicestatusCount',
            'hoststatusSum',
            'servicestatusSum',
            'hoststatusCountPercentage',
            'servicestatusCountPercentage'
        ]));
        $this->viewBuilder()->setOption('serialize', [
            'hoststatusCount',
            'servicestatusCount',
            'hoststatusSum',
            'servicestatusSum',
            'hoststatusCountPercentage',
            'servicestatusCountPercentage'
        ]);
    }

    public function menu() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $user = $this->getUser();
        $cacheKey = sprintf('Menu_%s', $user->get('id'));

        if (!Cache::read($cacheKey, 'permissions')) {
            $Menu = new Menu($this->PERMISSIONS);
            $jsonMenu = [];
            foreach ($Menu->getMenuItems() as $headline) {
                $jsonMenu[] = $headline->toArray();
            }

            Cache::write($cacheKey, $jsonMenu, 'permissions');
        }
        $session = $this->request->getSession();
        $session->close();

        $menu = Cache::read($cacheKey, 'permissions');

        $this->set('menu', $menu);
        $this->viewBuilder()->setOption('serialize', ['menu']);
    }

    public function menuControl() {
        //Only ship HTML template
        return;
    }

    public function topSearch() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if ($this->request->is('post')) {
            //Search request
            $type = $this->request->getData('type');
            $searchStr = $this->request->getData('searchStr');

            if ($type !== 'uuid') {
                throw new BadRequestException('Unknown type');
            }

            $tablesToSeach = [
                'Hosts',
                'Hosttemplates',
                'Timeperiods',
                'Commands',
                'Contacts',
                'Contactgroups',
                'Hostgroups',
                'Servicegroups',
                'Services',
                'Servicetemplates',
                'Hostescalations',
                'Serviceescalations',
                'Hostdependencies',
                'Servicedependencies'
            ];

            foreach ($tablesToSeach as $TableName) {
                /** @var Table $Table */
                $Table = TableRegistry::getTableLocator()->get($TableName);

                $result = $Table->find()
                    ->select([
                        'id',
                        'uuid'
                    ])
                    ->where([
                        'uuid' => $searchStr
                    ])
                    ->first();

                if ($result !== null) {
                    $hasPermission = $this->hasPermission('index', strtolower($TableName), '');
                    $this->set('hasPermission', $hasPermission);

                    if (!$hasPermission) {
                        $this->set('message', __('You are not permitted to access this object.'));
                        $this->viewBuilder()->setOption('serialize', [
                            'hasPermission',
                            'message'
                        ]);
                        $this->response = $this->response->withStatus(403);
                        return;
                    }

                    $this->set('state', $TableName . 'Index');
                    $this->set('id', $result->get('id'));
                    $this->viewBuilder()->setOption('serialize', [
                        'state',
                        'id',
                        'hasPermission'
                    ]);
                    return;
                }
            }
        }

        $this->set('message', __('Object could not be found.'));
        $this->viewBuilder()->setOption('serialize', [
            'message'
        ]);
        $this->response = $this->response->withStatus(404);
    }

    public function websocket_configuration() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!Cache::read('systemsettings', 'permissions')) {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettingsArray = $SystemsettingsTable->findAsArray();
            Cache::write('systemsettings', $systemsettingsArray, 'permissions');
        }
        $session = $this->request->getSession();
        $session->close();

        $systemsettings = Cache::read('systemsettings', 'permissions');
        $websocketConfig = $systemsettings['SUDO_SERVER'];
        $websocketConfig['SUDO_SERVER.URL'] = 'wss://' . env('HTTP_HOST') . '/sudo_server';
        $websocketConfig['QUERY_LOG.URL'] = 'wss://' . env('HTTP_HOST') . '/query_log';
        $websocketConfig['PUSH_NOTIFICATIONS.URL'] = 'wss://' . env('HTTP_HOST') . '/push_notifications';

        $this->set('websocket', $websocketConfig);
        $this->viewBuilder()->setOption('serialize', ['websocket']);
    }

    public function push_configuration() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!Cache::read('systemsettings', 'permissions')) {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettingsArray = $SystemsettingsTable->findAsArray();
            Cache::write('systemsettings', $systemsettingsArray, 'permissions');
        }
        $session = $this->request->getSession();
        $session->close();

        $systemsettings = Cache::read('systemsettings', 'permissions');
        $websocketConfig = $systemsettings['SUDO_SERVER'];
        $websocketConfig['PUSH_NOTIFICATIONS.URL'] = 'wss://' . env('HTTP_HOST') . '/push_notifications';


        $user = $this->getUser();

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        $this->set('user', [
            'id'             => $user->get('id'),
            'hasPushContact' => $ContactsTable->hasUserAPushContact($user->get('id'))
        ]);

        $this->set('websocket', $websocketConfig);
        $this->viewBuilder()->setOption('serialize', ['websocket', 'user']);
    }

    public function not_found() {
        //Only ship HTML template
        return;
    }

    public function forbidden() {
        //Only ship HTML template
        return;
    }

    public function executing() {
        //Only ship HTML template
        $id = $this->request->getQuery('id', 'angularExecutingModal');

        $this->set('id', $id);
    }

    public function acknowledge_service() {
        //Only ship HTML template
        return;
    }

    public function downtime_service() {
        //Only ship HTML template
        return;
    }

    public function reschedule_host() {
        //Only ship HTML template
        return;
    }

    public function enable_host_notifications() {
        //Only ship HTML template
        return;
    }

    public function disable_host_notifications() {
        //Only ship HTML template
        return;
    }

    public function downtime_host() {
        //Only ship HTML template

        if ($this->isAngularJsRequest()) {
            if (!Cache::read('FRONTEND.PRESELECTED_DOWNTIME_OPTION', 'permissions')) {
                /** @var SystemsettingsTable $SystemsettingsTable */
                $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
                $record = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.PRESELECTED_DOWNTIME_OPTION');
                Cache::write('FRONTEND.PRESELECTED_DOWNTIME_OPTION', $record->get('value'), 'permissions');
            }
            $downtimetypeId = Cache::read('FRONTEND.PRESELECTED_DOWNTIME_OPTION', 'permissions');

            $this->set('preselectedDowntimetype', $downtimetypeId);
            $this->viewBuilder()->setOption('serialize', ['preselectedDowntimetype']);
        }

        return;
    }

    public function acknowledge_host() {
        //Only ship HTML template
        return;
    }

    public function getDowntimeData() {
        if (!Cache::read('FRONTEND.PRESELECTED_DOWNTIME_OPTION', 'permissions')) {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $record = $SystemsettingsTable->getSystemsettingByKey('FRONTEND.PRESELECTED_DOWNTIME_OPTION');
            Cache::write('FRONTEND.PRESELECTED_DOWNTIME_OPTION', $record->get('value'), 'permissions');
        }
        $downtimetypeId = Cache::read('FRONTEND.PRESELECTED_DOWNTIME_OPTION', 'permissions');

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $defaultValues = [
            'from_date'       => $UserTime->customFormat('d.m.Y', time()),
            'from_time'       => $UserTime->customFormat('H:i', time()),
            'to_date'         => $UserTime->customFormat('d.m.Y', time()),
            'to_time'         => $UserTime->customFormat('H:i', time() + 60 * 15),
            'duration'        => 15,
            'comment'         => __('In maintenance'),
            'downtimetype_id' => $downtimetypeId
        ];

        $this->set('defaultValues', $defaultValues);
        $this->viewBuilder()->setOption('serialize', ['defaultValues']);
    }

    public function system_health() {
        $session = $this->request->getSession();
        $session->close();
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML template
            return;
        }

        $cache = Cache::read('system_health', 'permissions');
        if ($cache === null) {
            $status = [
                'cache_readable' => false,
                'state'          => 'unknown'
            ];
            $this->set('status', $status);
            $this->viewBuilder()->setOption('serialize', ['status']);
            return;
        }


        $cache['cache_readable'] = true;
        $cache['gearman_reachable'] = false;
        $cache['gearman_worker_running'] = false;

        $GearmanClient = new Gearman();
        $GearmanClient->setTimeout(5000);
        $cache['gearman_reachable'] = $GearmanClient->ping();


        exec('ps -eaf |grep gearman_worker |grep -v \'grep\'', $output);
        $cache['gearman_worker_running'] = sizeof($output) > 0;
        if (!$cache['gearman_worker_running']) {
            $this->setHealthState('critical');
        }

        if (!$cache['isNagiosRunning']) {
            $this->setHealthState('critical');
        }

        if (!$cache['isOitcCmdRunning']) {
            $this->setHealthState('warning');
        }

        if (!$cache['isSudoServerRunning']) {
            $this->setHealthState('warning');
        }

        if (!$cache['gearman_reachable'] || !$cache['gearman_worker_running']) {
            $this->setHealthState('critical');
        }

        if ($cache['isStatusengineInstalled'] && !$cache['isStatusengineRunning']) {
            $this->setHealthState('critical');
        }

        if ($cache['isStatusenginePerfdataProcessor'] && !$cache['isStatusengineRunning']) {
            $this->setHealthState('critical');
        }

        if (!$cache['isStatusenginePerfdataProcessor'] && !$cache['isNpcdRunning']) {
            $this->setHealthState('critical');
        }

        if ($cache['isDistributeModuleInstalled'] && !$cache['isNstaRunning']) {
            $this->setHealthState('warning');
        }

        $this->setHealthState($cache['memory_usage']['memory']['state']);
        $this->setHealthState($cache['memory_usage']['swap']['state']);
        $this->setHealthState($cache['load']['state']);
        foreach ($cache['disk_usage'] as $disk) {
            $this->setHealthState($disk['state']);
        }

        $user = $this->getUser();
        $UserTime = new UserTime($user->get('timezone'), $user->get('dateformat'));
        $cache['update'] = $UserTime->format($cache['update']);
        $cache['state'] = $this->state;
        $cache['errorCount'] = $this->errorCount;
        $this->set('status', $cache);
        $this->viewBuilder()->setOption('serialize', ['status']);
    }

    private function setHealthState($state) {
        if ($state !== 'ok') {
            $this->errorCount++;
        }

        //Do not overwrite critical with ok or warning
        if ($this->state === 'critical') {
            return;
        }

        //Do not overwrite warning with ok
        if ($this->state === 'warning' && $state !== 'critical') {
            return;
        }

        $this->state = $state;
    }

    public function mass_delete_host_downtimes() {
        return;
    }


    public function mass_delete_service_downtimes() {
        return;
    }

    public function submit_host_result() {
        return;
    }

    public function disable_host_flap_detection() {
        return;
    }

    public function enable_host_flap_detection() {
        return;
    }

    public function send_host_notification() {
        return;
    }

    public function submit_service_result() {
        return;
    }

    public function disable_service_flap_detection() {
        return;
    }

    public function enable_service_flap_detection() {
        return;
    }

    public function send_service_notification() {
        return;
    }

    public function enable_service_notifications() {
        //Only ship HTML template
        return;
    }

    public function disable_service_notifications() {
        //Only ship HTML template
        return;
    }

    /**
     * @param int $up up|ok
     * @param int $down down|warning
     * @param int $unreachable unreachable|critical
     * @param int $unknown unknown
     * @throws Exception
     */
    public function getPieChart($up = 0, $down = 0, $unreachable = 1, $unknown = null) {
        $session = $this->request->getSession();
        $session->close();
        $PieChart = new PieChart();

        $chartData = [$up, $down, $unreachable];
        if ($unknown !== null) {
            $chartData = [$up, $down, $unreachable, $unknown];
        }

        $PieChart->createPieChart($chartData);

        $image = $PieChart->getImage();


        $this->disableAutoRender();
        $this->response = $this->response->withHeader('Content-Type', 'image/png');
        $stream = new CallbackStream(function () use ($image) {
            imagepng($image, null, 0);
            imagedestroy($image);
        });
        $this->response = $this->response->withBody($stream);
    }

    /**
     * @param int $up up|ok
     * @param int $down down|warning
     * @param int $unreachable unreachable|critical
     * @param int $unknown unknown
     * @throws Exception
     */
    public function getHalfPieChart($up = 0, $down = 0, $unreachable = 1, $unknown = null) {
        $session = $this->request->getSession();
        $session->close();
        $PieChart = new PieChart();

        $chartData = [$up, $down, $unreachable];
        if ($unknown !== null) {
            $chartData = [$up, $down, $unreachable, $unknown];
        }

        $PieChart->createHalfPieChart($chartData);

        $image = $PieChart->getImage();

        $this->disableAutoRender();
        $this->response = $this->response->withHeader('Content-Type', 'image/png');
        $stream = new CallbackStream(function () use ($image) {
            imagepng($image, null, 0);
            imagedestroy($image);
        });
        $this->response = $this->response->withBody($stream);
    }

    /**
     * @param int $size
     * @param int $bitMaskHostState
     * @param int $bitMaskServiceState
     * @throws Exception
     */

    public function getHostAndServiceStateSummaryIcon($size = 100, $bitMaskHostState = 0, $bitMaskServiceState = 0) {
        $session = $this->request->getSession();
        $session->close();
        $HostAndServiceSummaryIcon = new HostAndServiceSummaryIcon($size);
        $HostAndServiceSummaryIcon->createSummaryIcon($bitMaskHostState, $bitMaskServiceState);
        $image = $HostAndServiceSummaryIcon->getImage();

        $this->autoRender = false;
        header('Content-Type: image/png');
        imagepng($image, null, 0);
        imagedestroy($image);
    }

    public function macros() {
        //Only ship HTML template
        return;
    }

    public function ldap_configuration() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!Cache::read('systemsettings', 'permissions')) {
            /** @var SystemsettingsTable $SystemsettingsTable */
            $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettingsArray = $SystemsettingsTable->findAsArray();
            Cache::write('systemsettings', $systemsettingsArray, 'permissions');
        }
        $session = $this->request->getSession();
        $session->close();

        $systemsettings = Cache::read('systemsettings', 'permissions');
        $ldapConfig = [
            'host'    => $systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS'],
            'query'   => $systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY'],
            'base_dn' => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN']
        ];

        $this->set('ldapConfig', $ldapConfig);
        $this->viewBuilder()->setOption('serialize', ['ldapConfig']);
    }

    public function priority() {
        //Only ship HTML template
        return;
    }

    public function intervalInput() {
        //Only ship HTML template
        return;
    }

    public function intervalInputWithDiffer() {
        //Only ship HTML template
        return;
    }

    public function colorpicker() {
        //Only ship HTML template
        return;
    }

    public function humanTime() {
        //Only ship HTML template
        return;
    }

    public function template_diff() {
        //Only ship HTML template
        return;
    }

    public function template_diff_button() {
        //Only ship HTML template
        return;
    }

    public function queryhandler() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $QueryHandler = new QueryHandler($SystemsettingsTable->getQueryHandlerPath());

        $this->set('QueryHandler', [
            'exists' => $QueryHandler->exists(),
            'path'   => $QueryHandler->getPath()
        ]);
        $this->viewBuilder()->setOption('serialize', ['QueryHandler']);

    }

    public function hostBrowserMenu() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $hostId = $this->request->getQuery('hostId');
        $includeHoststatus = $this->request->getQuery('includeHoststatus') === 'true';

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException('Invalid host');
        }
        $host = $HostsTable->getHostByIdWithHosttemplate($hostId);

        //Can user see this object?
        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
            return $this->render403();
        }

        //Can user edit this object?
        $allowEdit = $this->hasRootPrivileges;
        if ($allowEdit === false) {
            //Strict checking for non root users
            $allowEdit = $this->allowedByContainerId($host->getContainerIds());
        }

        /** @var DocumentationsTable $DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');


        $hostUrl = $host->get('host_url');
        if ($hostUrl === null || $hostUrl === '') {
            $hostUrl = $host->get('hosttemplate')->get('host_url');
        }

        if ($hostUrl) {
            $HostMacroReplacer = new HostMacroReplacer($host->toArray());
            $hostUrl = $HostMacroReplacer->replaceBasicMacros($hostUrl);
        }

        if ($includeHoststatus) {
            //Get meta data and push to front end
            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields->currentState()->isFlapping();
            $HosttatusTable = $this->DbBackend->getHoststatusTable();
            $hoststatus = $HosttatusTable->byUuid($host->get('uuid'), $HoststatusFields);
            if (!isset($hoststatus['Hoststatus'])) {
                $hoststatus['Hoststatus'] = [];
            }
            $Hoststatus = new Hoststatus($hoststatus['Hoststatus']);
        } else {
            $Hoststatus = new Hoststatus([
                'Hoststatus' => []
            ]);

        }

        $config = [
            'hostId'            => $host->get('id'),
            'hostUuid'          => $host->get('uuid'),
            'hostName'          => $host->get('name'),
            'hostAddress'       => $host->get('address'),
            'docuExists'        => $DocumentationsTable->existsByUuid($host->get('uuid')),
            'hostUrl'           => $hostUrl,
            'allowEdit'         => $allowEdit,
            'includeHoststatus' => $includeHoststatus,
            'Hoststatus'        => $Hoststatus->toArray()
        ];

        $this->set('config', $config);
        $this->viewBuilder()->setOption('serialize', ['config']);
    }

    public function serviceBrowserMenu() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $serviceId = $this->request->getQuery('serviceId');
        $includeServicestatus = $this->request->getQuery('includeServicestatus') === 'true';

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        if (!$ServicesTable->existsById($serviceId)) {
            throw new NotFoundException('Invalid service');
        }
        $service = $ServicesTable->getServiceByIdWithHostAndServicetemplate($serviceId);

        //Can user see this object?
        if (!$this->allowedByContainerId($service->getContainerIds(), false)) {
            return $this->render403();
        }

        //Can user edit this object?
        $allowEdit = $this->hasRootPrivileges;
        if ($allowEdit === false) {
            //Strict checking for non root users
            $allowEdit = $this->allowedByContainerId($service->getContainerIds());
        }

        /** @var DocumentationsTable $DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');

        $serviceName = $service->get('name');
        if ($serviceName === null || $serviceName === '') {
            $serviceName = $service->get('servicetemplate')->get('name');
        }


        $serviceUrl = $service->get('service_url');
        if ($serviceUrl === null || $serviceUrl === '') {
            $serviceUrl = $service->get('servicetemplate')->get('service_url');
        }

        if ($serviceUrl) {
            $HostMacroReplacer = new HostMacroReplacer($service->get('host')->toArray());
            $ServiceMacroReplacer = new ServiceMacroReplacer($service->toArray());
            $serviceUrl = $HostMacroReplacer->replaceBasicMacros($serviceUrl);
            $serviceUrl = $ServiceMacroReplacer->replaceBasicMacros($serviceUrl);
        }

        if ($includeServicestatus) {
            //Get meta data and push to front end
            $ServicestatusFields = new ServicestatusFields($this->DbBackend);
            $ServicestatusFields->currentState()->isFlapping();
            $ServicestatusTable = $this->DbBackend->getServicestatusTable();
            $servicestatus = $ServicestatusTable->byUuid($service->get('uuid'), $ServicestatusFields);
            if (!isset($servicestatus['Servicestatus'])) {
                $servicestatus['Servicestatus'] = [];
            }
            $Servicestatus = new Servicestatus($servicestatus['Servicestatus']);
        } else {
            $Servicestatus = new Servicestatus([
                'Servicestatus' => []
            ]);
        }

        $config = [
            'hostId'               => $service->get('host')->get('id'),
            'serviceUuid'          => $service->get('uuid'),
            'hostName'             => $service->get('host')->get('name'),
            'serviceName'          => $serviceName,
            'hostAddress'          => $service->get('host')->get('address'),
            'docuExists'           => $DocumentationsTable->existsByUuid($service->get('uuid')),
            'serviceUrl'           => $serviceUrl,
            'allowEdit'            => $allowEdit,
            'includeServicestatus' => $includeServicestatus,
            'Servicestatus'        => $Servicestatus->toArray()
        ];
        $this->set('config', $config);
        $this->viewBuilder()->setOption('serialize', ['config']);
    }

    public function durationInput() {
        //Only ship HTML template
        return;
    }

    public function calendar() {
        //Only ship HTML template
        return;
    }

    public function reload_required() {
        //Only ship HTML template
        return;
    }

    public function sidebar() {
        //Only ship HTML template
        $this->set('hasRootPrivileges', $this->hasRootPrivileges);
        return;
    }
}
