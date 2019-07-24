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

use App\Model\Table\ContactsTable;
use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\HostMacroReplacer;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\HostAndServiceSummaryIcon;
use itnovum\openITCOCKPIT\Core\Views\PieChart;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Monitoring\QueryHandler;

/**
 * Class AngularController
 * @property  Host Host
 * @property Service Service
 * @property AppAuthComponent Auth
 * @property MenuComponent Menu
 * @property Contact Contact
 */
class AngularController extends AppController {

    public $layout = 'blank';
    public $components = ['GearmanClient'];
    public $uses = [
        'Host',
        'Service',
        'Container',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Contact'
    ];

    public function index() {
        //Shipp the AngularJS Single Page Application layout
        //URL: /spa#!/<state>
        $this->layout = 'angularjs_spa';
        return;
    }

    public function paginator() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function scroll() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function mass_delete() {
        return;
    }

    public function confirm_delete() {
        return;
    }

    public function confirm_deactivate() {
        return;
    }

    public function mass_activate() {
        return;
    }

    public function mass_deactivate() {
        return;
    }

    public function export() {
        return;
    }

    private $state = 'unknown';

    public function user_timezone() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        session_write_close();

        $userTimezone = $this->Auth->user('timezone');
        if (strlen($userTimezone) < 2) {
            $userTimezone = 'Europe/Berlin';
        }
        $UserTime = new DateTime($userTimezone);
        $ServerTime = new DateTime();

        $timezone = [
            'user_timezone'          => $userTimezone,
            'user_offset'            => $UserTime->getOffset(),
            'server_time_utc'        => time(),
            'server_time'            => date('F d, Y H:i:s'),
            'server_timezone_offset' => $ServerTime->getOffset()
        ];
        $this->set('timezone', $timezone);
        $this->set('_serialize', ['timezone']);
    }

    public function version_check() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $path = OLD_APP . 'Lib' . DS . 'AvailableVersion.php';
        $availableVersion = '???';
        if (file_exists($path)) {
            require_once $path;
            $availableVersion = openITCOCKPIT_AvailableVersion::get();
        }
        Configure::load('version');
        $newVersionAvailable = false;
        if (version_compare($availableVersion, Configure::read('version')) > 0 && $this->hasRootPrivileges) {
            $newVersionAvailable = true;
        }

        $this->set('newVersionAvailable', $newVersionAvailable);
        $this->set('_serialize', ['newVersionAvailable']);
    }

    public function menustats() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        session_write_close();


        $showstatsinmenu = (bool)$this->Auth->user('showstatsinmenu');
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
            if ($this->DbBackend->isNdoUtils()) {
                $hoststatusCount = $this->Host->getHoststatusCount($this->MY_RIGHTS, false);
                $servicestatusCount = $this->Host->getServicestatusCount($this->MY_RIGHTS, false);
            }

            if ($this->DbBackend->isCrateDb()) {
                $hoststatusCount = $this->Hoststatus->getHoststatusCount($this->MY_RIGHTS, false);
                $servicestatusCount = $this->Servicestatus->getServicestatusCount($this->MY_RIGHTS, false);
            }

            if ($this->DbBackend->isStatusengine3()) {
                $hoststatusCount = $this->Host->getHoststatusCountStatusengine3($this->MY_RIGHTS, false);
                $servicestatusCount = $this->Host->getServicestatusCountStatusengine3($this->MY_RIGHTS, false);
            }

        }
        $this->set(compact(['showstatsinmenu', 'hoststatusCount', 'servicestatusCount']));
        $this->set('_serialize', ['showstatsinmenu', 'hoststatusCount', 'servicestatusCount']);
    }

    public function statuscount() {
        if (!$this->isApiRequest()) {
            throw new RuntimeException('Only for API requests');
        }
        session_write_close();

        $recursive = false;
        if ($this->request->query('recursive') === 'true') {
            $recursive = true;
        }

        $containerIds = $this->request->query('containerIds');
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
            $hoststatusCount = $this->Host->getHoststatusCount($containerIdsForQuery, true);
            $servicestatusCount = $this->Host->getServicestatusCount($containerIdsForQuery, true);
        }

        if ($this->DbBackend->isCrateDb()) {
            $hoststatusCount = $this->Hoststatus->getHoststatusCount($containerIdsForQuery, true);
            $servicestatusCount = $this->Servicestatus->getServicestatusCount($containerIdsForQuery, true);
        }

        if ($this->DbBackend->isStatusengine3()) {
            $hoststatusCount = $this->Host->getHoststatusCountStatusengine3($containerIdsForQuery, true);
            $servicestatusCount = $this->Host->getServicestatusCountStatusengine3($containerIdsForQuery, true);
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
        $this->set('_serialize', [
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

        $User = new User($this->Auth);
        $cacheKey = sprintf('Menu_%s', $User->getId());

        if (!Cache::read($cacheKey, 'permissions')) {
            $menu = $this->Menu->compileMenu();
            $menu = $this->Menu->filterMenuByAcl($menu, $this->PERMISSIONS, true);
            $menu = $this->Menu->forAngular($menu);
            Cache::write($cacheKey, $menu, 'permissions');
        }
        session_write_close();

        $menu = Cache::read($cacheKey, 'permissions');

        $this->set('menu', $menu);
        $this->set('_serialize', ['menu']);
    }

    public function websocket_configuration() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!Cache::read('systemsettings', 'permissions')) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettingsArray = $Systemsettings->findAsArray();
            Cache::write('systemsettings', $systemsettingsArray, 'permissions');
        }
        session_write_close();

        $systemsettings = Cache::read('systemsettings', 'permissions');
        $websocketConfig = $systemsettings['SUDO_SERVER'];
        $websocketConfig['SUDO_SERVER.URL'] = 'wss://' . env('HTTP_HOST') . '/sudo_server';
        $websocketConfig['QUERY_LOG.URL'] = 'wss://' . env('HTTP_HOST') . '/query_log';
        $websocketConfig['PUSH_NOTIFICATIONS.URL'] = 'wss://' . env('HTTP_HOST') . '/push_notifications';

        $this->set('websocket', $websocketConfig);
        $this->set('_serialize', ['websocket']);
    }

    public function push_configuration() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        if (!Cache::read('systemsettings', 'permissions')) {
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettingsArray = $Systemsettings->findAsArray();
            Cache::write('systemsettings', $systemsettingsArray, 'permissions');
        }
        session_write_close();

        $systemsettings = Cache::read('systemsettings', 'permissions');
        $websocketConfig = $systemsettings['SUDO_SERVER'];
        $websocketConfig['PUSH_NOTIFICATIONS.URL'] = 'wss://' . env('HTTP_HOST') . '/push_notifications';


        $User = new User($this->Auth);

        /** @var $ContactsTable ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');

        $this->set('user', [
            'id'             => $User->getId(),
            'hasPushContact' => $ContactsTable->hasUserAPushContact($User->getId())
        ]);

        $this->set('websocket', $websocketConfig);
        $this->set('_serialize', ['websocket', 'user']);
    }

    public function not_found() {
        $this->layout = 'blank';
        //Only ship HTML template
        return;
    }

    public function forbidden() {
        $this->layout = 'blank';
        //Only ship HTML template
        return;
    }

    public function executing() {
        //Only ship HTML template
        if (!isset($this->request->query['id'])) {
            $id = 'angularExecutingModal';
        } else {
            $id = $this->request->query['id'];
        }
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
            $preselectedDowntimetype = $this->Systemsetting->findByKey("FRONTEND.PRESELECTED_DOWNTIME_OPTION");
            $this->set('preselectedDowntimetype', $preselectedDowntimetype['Systemsetting']['value']);
            $this->set('_serialize', ['preselectedDowntimetype']);
        }

        return;
    }

    public function acknowledge_host() {
        //Only ship HTML template
        return;
    }

    public function getDowntimeData() {
        $this->layout = 'angularjs';
        if (!$this->isAngularJsRequest()) {
            return;
        }

        $refill = [
            'from_date' => date('d.m.Y'),
            'from_time' => date('H:i'),
            'to_date'   => date('d.m.Y'),
            'to_time'   => date('H:i', time() + 60 * 15),
            'duration'  => "15",
            'comment'   => __('In maintenance')
        ];

        $this->set('refill', $refill);
        $this->set('_serialize', ['refill']);
    }

    public function system_health() {
        session_write_close();
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML template
            return;
        }

        $cache = Cache::read('system_health', 'permissions');
        if ($cache === false) {
            $status = [
                'cache_readable' => false,
                'state'          => 'unknown'
            ];
            $this->set('status', $status);
            $this->set('_serialize', ['status']);
            return;
        }


        $cache['cache_readable'] = true;
        $cache['gearman_reachable'] = false;
        $cache['gearman_worker_running'] = false;

        $this->GearmanClient->client->setTimeout(5000);
        $cache['gearman_reachable'] = @$this->GearmanClient->client->ping(true);


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

        if ($cache['isDistributeModuleInstalled'] && !$cache['isPhpNstaRunning']) {
            $this->setHealthState('warning');
        }

        $this->setHealthState($cache['memory_usage']['memory']['state']);
        $this->setHealthState($cache['memory_usage']['swap']['state']);
        $this->setHealthState($cache['load']['state']);
        foreach ($cache['disk_usage'] as $disk) {
            $this->setHealthState($disk['state']);
        }

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
        $cache['update'] = $UserTime->format($cache['update']);
        $cache['state'] = $this->state;
        $this->set('status', $cache);
        $this->set('_serialize', ['status']);
    }

    private function setHealthState($state) {
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
        session_write_close();
        $PieChart = new PieChart();

        $chartData = [$up, $down, $unreachable];
        if ($unknown !== null) {
            $chartData = [$up, $down, $unreachable, $unknown];
        }

        $PieChart->createPieChart($chartData);

        $image = $PieChart->getImage();

        $this->layout = false;
        $this->render = false;
        header('Content-Type: image/png');
        imagepng($image, null, 0);
        imagedestroy($image);
    }

    /**
     * @param int $up up|ok
     * @param int $down down|warning
     * @param int $unreachable unreachable|critical
     * @param int $unknown unknown
     * @throws Exception
     */
    public function getHalfPieChart($up = 0, $down = 0, $unreachable = 1, $unknown = null) {
        session_write_close();
        $PieChart = new PieChart();

        $chartData = [$up, $down, $unreachable];
        if ($unknown !== null) {
            $chartData = [$up, $down, $unreachable, $unknown];
        }

        $PieChart->createHalfPieChart($chartData);

        $image = $PieChart->getImage();

        $this->layout = false;
        $this->render = false;
        header('Content-Type: image/png');
        imagepng($image, null, 0);
        imagedestroy($image);
    }

    /**
     * @param int $size
     * @param int $bitMaskHostState
     * @param int $bitMaskServiceState
     * @throws Exception
     */

    public function getHostAndServiceStateSummaryIcon($size = 100, $bitMaskHostState = 0, $bitMaskServiceState = 0) {
        session_write_close();
        $HostAndServiceSummaryIcon = new HostAndServiceSummaryIcon($size);
        $HostAndServiceSummaryIcon->createSummaryIcon($bitMaskHostState, $bitMaskServiceState);
        $image = $HostAndServiceSummaryIcon->getImage();

        $this->layout = false;
        $this->render = false;
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
            /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
            $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
            $systemsettingsArray = $Systemsettings->findAsArray();
            Cache::write('systemsettings', $systemsettingsArray, 'permissions');
        }
        session_write_close();

        $systemsettings = Cache::read('systemsettings', 'permissions');
        $ldapConfig = [
            'host'    => $systemsettings['FRONTEND']['FRONTEND.LDAP.ADDRESS'],
            'query'   => $systemsettings['FRONTEND']['FRONTEND.LDAP.QUERY'],
            'base_dn' => $systemsettings['FRONTEND']['FRONTEND.LDAP.BASEDN']
        ];

        $this->set('ldapConfig', $ldapConfig);
        $this->set('_serialize', ['ldapConfig']);
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

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $QueryHandler = new QueryHandler($Systemsettings->getQueryHandlerPath());

        $this->set('QueryHandler', [
            'exists' => $QueryHandler->exists(),
            'path'   => $QueryHandler->getPath()
        ]);
        $this->set('_serialize', ['QueryHandler']);

    }

    public function hostBrowserMenu() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $hostId = $this->request->query('hostId');
        $includeHoststatus = $this->request->query('includeHoststatus') === 'true';

        /** @var $HostsTable App\Model\Table\HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException('Invalid host');
        }
        $host = $HostsTable->getHostByIdWithHosttemplate($hostId);

        //Can user see this object?
        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
            $this->render403();
            return;
        }

        //Can user edit this object?
        $allowEdit = $this->allowedByContainerId($host->getContainerIds());

        /** @var $DocumentationsTable App\Model\Table\DocumentationsTable */
        $DocumentationsTable = TableRegistry::getTableLocator()->get('Documentations');


        $hostUrl = $host->get('host_url');
        if ($hostUrl === null || $hostUrl === '') {
            $hostUrl = $host->get('hosttemplate')->get('host_url');
        }

        if ($hostUrl) {
            $HostMacroReplacer = new HostMacroReplacer($host);
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
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatus['Hoststatus']);
        } else {
            $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([
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
        $this->set('_serialize', ['config']);

    }
}
