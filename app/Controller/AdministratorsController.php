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

use itnovum\openITCOCKPIT\Core\RepositoryChecker;

/**
 * @property Systemsetting Systemsetting
 */
class AdministratorsController extends AppController
{
    public $components = ['GearmanClient'];
    public $uses = ['Proxy'];
    public $layout = 'Admin.default';

    function index()
    {
    }

    function debug()
    {
        $this->loadModel('Systemsetting');
        $this->loadModel('Cronjob');
        $this->loadModel('Register');


        $systemsetting = $this->Systemsetting->findAsArray();
        $this->set('systemsetting', $systemsetting);


        Configure::load('gearman');
        $this->Config = Configure::read('gearman');

        $this->GearmanClient->client->setTimeout(5000);
        $gearmanReachable = @$this->GearmanClient->client->ping(true);
        $this->set('gearmanReachable', $gearmanReachable);


        $isGearmanWorkerRunning = false;
        exec('ps -eaf |grep gearman_worker |grep -v \'grep\'', $output);
        if (sizeof($output) > 0) {
            $isGearmanWorkerRunning = true;
        }
        $this->set('isGearmanWorkerRunning', $isGearmanWorkerRunning);


        //Check if load cronjob exists
        if (!$this->Cronjob->checkForCronjob('CpuLoad', 'Core')) {
            //Cron does not exists, so we create it
            $this->Cronjob->add('CpuLoad', 'Core', 15);
        }

        $license = $this->Register->find('first');
        $isEnterprise = false;
        if (!empty($license)) {
            $isEnterprise = true;
        }

        $load = null;

        if (file_exists(TMP.'loadavg')) {
            $this->Frontend->setJson('renderGraph', true);
            $load = file(TMP.'loadavg');
            if (sizeof($load) >= 3) {
                $graphData = [
                    1  => [],
                    5  => [],
                    15 => [],
                ];
                foreach ($load as $line) {
                    $line = explode(' ', $line);
                    $graphData[1][($line[0] * 1000)] = $line[1];
                    $graphData[5][($line[0] * 1000)] = $line[2];
                    $graphData[15][($line[0] * 1000)] = $line[3];
                }
                $this->Frontend->setJson('graphData', $graphData);
            } else {
                if (file_exists('/proc/loadavg')) {
                    $load = file('/proc/loadavg');
                    $load = explode(' ', $load[0]);
                    $this->Frontend->setJson('renderGraph', false);
                }
            }
        } else {
            if (file_exists('/proc/loadavg')) {
                $load = file('/proc/loadavg');
                $load = explode(' ', $load[0]);
                $this->Frontend->setJson('renderGraph', false);
            }
        }


        $output = null;
        exec('LANG=C df -h', $output, $returncode);
        $disks = [];
        if ($returncode == 0) {
            $ignore = ['none', 'udev', 'Filesystem'];
            foreach ($output as $line) {
                $value = preg_split('/\s+/', $line);
                if (!in_array($value[0], $ignore) && $value[5] != '/run') {
                    $disks[] = [
                        'disk'       => $value[0],
                        'size'       => $value[1],
                        'used'       => $value[2],
                        'avail'      => $value[3],
                        'use%'       => str_replace('%', '', $value[4]),
                        'mountpoint' => $value[5],
                    ];
                }
            }
        }

        /*
         * Uncommented because of reading memory parameters from /proc/meminfo instead
        $output = null;
        exec('LANG=C free -m', $output, $returncode);
        $memory = [];
        if ($returncode == 0) {
            foreach ($output as $line) {
                $value = preg_split('/\s+/', $line);
                if ($value[0] == 'Mem:') {
                    $memory['Memory'] = [
                        'total'   => $value[1],
                        'used'    => $value[2],
                        'free'    => $value[3],
                        'buffers' => $value[5],
                        'cached'  => $value[6],
                    ];
                }

                //if ($value[0] == '-/+') {
                //    $memory['Memory']['used'] = $value[2];
                //}

                if ($value[0] == 'Swap:') {
                    $memory['Swap'] = [
                        'total' => $value[1],
                        'used'  => $value[2],
                        'free'  => $value[3],
                    ];
                }
            }
        }
        */

        $data = explode("\n", file_get_contents("/proc/meminfo"));
        $meminfo = array();
        foreach ($data as $line) {
            $temp = explode(":", $line);
            if (isset($temp[1])) {
                $meminfo[$temp[0]] = intval(trim(intval(substr($temp[1],0, strpos($temp[1], "kB"))))/1000);
            }
        }

        $memory['Memory'] = [
            'total'   => $meminfo['MemTotal'],
            'used'    => $meminfo['Active'],
            'free'    => $meminfo['MemFree'],
            'buffers' => $meminfo['Buffers'],
            'cached'  => $meminfo['Cached'],
        ];

        $memory['Swap'] = [
            'total' => $meminfo['SwapTotal'],
            'used'  => $meminfo['SwapTotal'] - $meminfo['SwapFree'],
            'free'  => $meminfo['SwapFree'],
        ];

        $osVersion = PHP_OS;
        if (file_exists('/etc/os-release')) {
            foreach (file('/etc/os-release') as $_line) {
                $line = explode('=', $_line, 2);
                if ($line[0] == 'PRETTY_NAME') {
                    $osVersion = str_replace('"', '', $line[1]);
                }
            }
        }
        $this->set('osVersion', $osVersion);

        if ($gearmanReachable && $isGearmanWorkerRunning) {
            //Check if your background proesses are running
            $backgroundProcessStatus = $this->GearmanClient->send('check_background_processes');
            $this->set('backgroundProcessStatus', $backgroundProcessStatus);
        }

        $isNdoInstalled = false;
        if (file_exists('/opt/openitc/nagios/bin/ndo2db')) {
            $isNdoInstalled = true;
        }

        $isStatusengineInstalled = false;
        if (file_exists('/opt/statusengine/cakephp/app/Console/Command/StatusengineLegacyShell.php')) {
            $isStatusengineInstalled = true;
        }

        $this->set([
            'isNdoInstalled'          => $isNdoInstalled,
            'isStatusengineInstalled' => $isStatusengineInstalled,
        ]);

        $isNpcdInstalled = false;
        if (file_exists('/opt/openitc/nagios/bin/npcd')) {
            $isNpcdInstalled = true;
        }

        $isStatusenginePerfdataProcessor = false;
        $statusengineConfig = '/opt/statusengine/cakephp/app/Config/Statusengine.php';
        if (file_exists($statusengineConfig)) {
            require_once $statusengineConfig;
            if (isset($config['process_perfdata'])) {
                if ($config['process_perfdata'] === true) {
                    $isStatusenginePerfdataProcessor = true;
                }
            }
        }

        $this->set('isStatusenginePerfdataProcessor', $isStatusenginePerfdataProcessor);


        $output = null;
        exec($systemsetting['INIT']['INIT.GEARMAN_JOB_SERVER_STATUS'], $output, $returncode);
        if ($returncode == 0) {
            $is_gearmand_running = true;
            $output = null;
            exec('gearadmin --status', $output);
            //Parse output
            $trash = array_pop($output);
            foreach ($output as $line) {
                $queueDetails = explode("\t", $line);
                $gearmanStatus[$queueDetails[0]] = [
                    'jobs'    => $queueDetails[1],
                    'running' => $queueDetails[2],
                    'worker'  => $queueDetails[3],
                ];
            }
        }

        //Get Monitoring engine + version
        $output = null;
        Configure::load('nagios');
        exec(Configure::read('nagios.basepath').Configure::read('nagios.bin').Configure::read('nagios.nagios_bin').' --version | head -n 2', $output);
        $monitoring_engine = $output[1];

        App::uses('CakeEmail', 'Network/Email');
        $Email = new ItcMail();
        $Email->config('default');
        $mailConfig = $Email->getConfig();

        $recipientAddress = $this->Auth->user('email');


        $RepositoryChecker = new RepositoryChecker();

        $this->set('isDebuggingMode', Configure::read('debug') == 2);
        $this->set(compact([
            'disks',
            'memory',
            'load',
            'isEnterprise',
            'monitoring_engine',
            'mailConfig',
            'gearmanStatus',
            'recipientAddress',
            'RepositoryChecker'
        ]));
    }

    public function testMail()
    {
        try {
            $this->loadModel('Systemsetting');
            $recipientAddress = $this->Auth->user('email');
            $_systemsettings = $this->Systemsetting->findAsArray();

            $Email = new CakeEmail();
            $Email->config('default');
            $Email->from([$_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
            $Email->to($recipientAddress);
            $Email->subject(__('System test mail'));

            $Email->emailFormat('both');
            $Email->template('template-testmail', 'template-testmail');

            $Email->send();
            $this->setFlash(__('Test mail send to: %s', $recipientAddress));

            return $this->redirect(['action' => 'debug']);
        } catch (Exception $ex) {
            $this->setFlash(__('An error occured while sending test mail: %s', $ex->getMessage()), false);
            return $this->redirect(['action' => 'debug']);
        }
    }

    public function querylog(){
        $this->layout = 'angularjs';
    }
}

App::uses('CakeEmail', 'Network/Email');

class ItcMail extends CakeEmail
{

    public function __construct($config = null)
    {
        parent::__construct($config);
    }

    public function getConfig($removePassword = true)
    {
        if ($removePassword === true) {
            $config = $this->_config;
            unset($config['password']);

            return $config;
        }

        return $this->_config;
    }
}
