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

class ExportsController extends AppController {
    public $layout = 'Admin.default';

    public $components = [
        'ListFilter.ListFilter',
        'RequestHandler',
        'AdditionalLinks',
        'GearmanClient',
    ];
    public $helpers = [
        'ListFilter.ListFilter',
    ];

    public function index() {
        App::uses('UUID', 'Lib');
        Configure::load('gearman');
        $this->Config = Configure::read('gearman');

        $this->GearmanClient->client->setTimeout(5000);
        $gearmanReachable = @$this->GearmanClient->client->ping(true);

        $exportRunning = true;
        $result = $this->Export->findByTask('export_started');
        if (empty($result)) {
            $exportRunning = false;
        } else {
            if ($result['Export']['finished'] == 1) {
                $exportRunning = false;
            }
        }

        $this->loadModel('Systemsetting');

        $monitoringSystemsettings = $this->Systemsetting->findAsArraySection('MONITORING');

        $this->set('gearmanReachable', $gearmanReachable);
        $this->set('monitoringSystemsettings', $monitoringSystemsettings);
        $this->set('exportRunning', $exportRunning);
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);
        $this->Frontend->setJson('exportRunning', $exportRunning);
        $this->Frontend->setJson('uuidRegEx', UUID::JSregex());
    }

    public function broadcast() {
        $this->allowOnlyAjaxRequests();
        $_exportRecords = $this->Export->find('all');

        $exportRecords = [];

        $exportFinished = [
            'finished' => false,
            'successfully' => false,
        ];

        foreach ($_exportRecords as $exportRecord) {
            $exportRecords[$exportRecord['Export']['id']] = [
                'task' => $exportRecord['Export']['task'],
                'text' => h($exportRecord['Export']['text']),
                'finished' => $exportRecord['Export']['finished'],
                'successfully' => $exportRecord['Export']['successfully'],
            ];

            if ($exportRecord['Export']['task'] == 'export_finished' && $exportRecord['Export']['finished'] == 1) {
                $exportFinished = [
                    'finished' => true,
                    'successfully' => (bool)$exportRecord['Export']['successfully'],
                ];
            }
        }

        $this->set(compact(['exportRecords', 'exportFinished']));
        $this->set('_serialize', ['exportRecords', 'exportFinished']);
    }

    public function launchExport($createBackup = 1) {

        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        if (isset($this->request->query['instances'])) {
            $instancesToExport = $this->request->query['instances'];
            if (is_dir(APP . 'Plugin' . DS . 'DistributeModule')) {
                $SatelliteModel = ClassRegistry::init('DistributeModule.Satellite', 'Model');
                $SatelliteModel->disableAllInstanceConfigSyncs();
                $SatelliteModel->saveInstancesForConfigSync($instancesToExport);
            }
        }

        //session_write_close();

        $exportRunning = true;
        $result = $this->Export->findByTask('export_started');
        if (empty($result)) {
            $exportRunning = false;
        } else {
            if ($result['Export']['finished'] == 1) {
                $exportRunning = false;
            }
        }

        $exportStarted = false;
        if ($exportRunning === false) {
            //Remove old records from DB that javascript is not confused

            $this->Export->deleteAll(true);

            Configure::load('gearman');
            $this->Config = Configure::read('gearman');
            $this->GearmanClient->client->doBackground("oitc_gearman", Security::cipher(serialize(['task' => 'export_start_export', 'backup' => (int)$createBackup]), $this->Config['password']));
            $exportStarted = true;
        }

        $export = [
            'exportRunning' => $exportRunning,
            'exportStarted' => $exportStarted,
        ];
        $this->set('export', $export);
        $this->set('_serialize', ['export']);
    }

    public function saveInstanceConfigSyncSelection() {
        if (!$this->request->is('ajax')) {
            throw new MethodNotAllowedException();
        }

        if (is_dir(APP . 'Plugin' . DS . 'DistributeModule')) {
            $SatelliteModel = ClassRegistry::init('DistributeModule.Satellite', 'Model');
            $SatelliteModel->disableAllInstanceConfigSyncs();
            if (isset($this->request->query['instances'])) {
                $instancesToExport = $this->request->query['instances'];
                $SatelliteModel->saveInstancesForConfigSync($instancesToExport);
            }
        }
        $result = true;
        $this->set('_serialize', ['result']);
    }

    public function verifyConfig() {
        //$this->allowOnlyAjaxRequests();
        Configure::load('gearman');
        $this->Config = Configure::read('gearman');
        $result = $this->GearmanClient->client->doNormal("oitc_gearman", Security::cipher(serialize(['task' => 'export_verify_config']), $this->Config['password']));
        $result = unserialize($result);
        $this->set('result', $result);
        $this->set('_serialize', ['result']);
    }
}
