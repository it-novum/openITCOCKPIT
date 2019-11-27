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

declare(strict_types=1);

namespace App\Controller;

class BackupsController extends AppController {
    public $layout = 'Admin.default';
    public $components = ['GearmanClient'];

    public function index() {
        $backup_files = $this->getBackupFiles();

        $this->set(compact('backup_files'));
        $this->set('_serialize', ['backup_files']);
    }

    public function backup() {
        $filenameForBackup = $this->request->query['filename'] . "_" . date("Y-m-d_His") . ".sql";
        if (preg_match('/^[a-zA-Z0-9_\-]*$/', $this->request->query['filename'])) {
            $error = false;
            $backupRunning = true;
        } else {
            $error = true;
            $backupRunning = false;
        }

        if (!$error) {
            Configure::load('gearman');
            $this->Config = Configure::read('gearman');
            $this->GearmanClient->client->doBackground("oitc_gearman", serialize(['task' => 'make_sql_backup', 'filename' => $filenameForBackup]));
        }

        $backup = [
            'backupRunning' => $backupRunning,
            'error'         => $error,
        ];

        $this->set('backup', $backup);
        $this->set('_serialize', ['backup']);
    }

    public function restore() {
        $pathForRestore = $this->request->query['backupfile'];
        Configure::load('gearman');
        $this->Config = Configure::read('gearman');
        $this->GearmanClient->client->doBackground("oitc_gearman", serialize(['task' => 'restore_sql_backup', 'path' => $pathForRestore]));
        $backup = [
            'backupRunning' => true,
        ];
        $this->set('backup', $backup);
        $this->set('_serialize', ['backup']);
    }

    public function checkBackupFinished() {
        $this->allowOnlyAjaxRequests();
        $backup_files = $this->getBackupFiles();
        $backupFinished = [];
        $finished = false;
        $error = false;
        $fileBackup = "/opt/openitc/nagios/backup/finishBackup.txt";
        $fileRestore = "/opt/openitc/nagios/backup/finishRestore.txt";
        if (file_exists($fileBackup)) {
            $finished = true;
            $error = false;
            $this->Config = Configure::read('gearman');
            $this->GearmanClient->client->doNormal("oitc_gearman", serialize(['task' => 'delete_sql_backup', 'path' => $fileBackup]));
        } else if (file_exists($fileRestore)) {
            $finished = true;
            $error = false;
            $this->Config = Configure::read('gearman');
            $this->GearmanClient->client->doNormal("oitc_gearman", serialize(['task' => 'delete_sql_backup', 'path' => $fileRestore]));
        } else {
            $finished = false;
            $error = false;
        }

        $backupFinished = [
            'finished'     => $finished,
            'error'        => $error,
            'backup_files' => $backup_files,
        ];

        $this->set('backupFinished', $backupFinished);
        $this->set('_serialize', ['backupFinished']);
    }

    public function deleteBackupFile() {
        $fileToDelete = $this->request->query['fileToDelete'];

        $this->Config = Configure::read('gearman');
        $result = $this->GearmanClient->client->doNormal("oitc_gearman", serialize(['task' => 'delete_sql_backup', 'path' => $fileToDelete]));

        $result = unserialize($result);
        $backup_files = $this->getBackupFiles();

        $success = [
            'result'       => $result,
            'backup_files' => $backup_files,
        ];


        $this->set('success', $success);
        $this->set('_serialize', ['success']);
    }

    private function getBackupFiles() {
        $backup_files = [];
        $files = scandir("/opt/openitc/nagios/backup/");
        foreach ($files as $file) {
            if (strstr($file, ".sql")) {
                $backup_files["/opt/openitc/nagios/backup/" . $file] = $file;
            }
        }
        return $backup_files;
    }
}
