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

class BackupsController extends AppController{
    public $layout = 'Admin.default';
    public $components = ['Http', 'GearmanClient'];
    public $uses = ['Proxy'];


    public function index(){
        $backupfiles = array();
        $files =  scandir("/opt/openitc/nagios/backup/");
        foreach ($files as $file) {
            if (strstr($file,"mysql_oitc_bkp_")) {
                array_push($backupfiles, "/opt/openitc/nagios/backup/" . $file . "/");
            }
        }
        $this->set(compact('backupfiles'));
        $this->set('_serialize', ['backupfiles']);
    }

    public function backup(){
        $this->makeSQLBackup(Configure::read('nagios.export.backupTarget'));
    }

    private function makeSQLBackup($path) {
        $pathForBackup = $path."mysql_oitc_bkp_".date("Ymd_His")."/";
        debug($pathForBackup);
        die();
        //exec("xtrabackup --backup --target-dir=".$pathForBackup, $output, $return);
        //exec("xtrabackup --prepare --target-dir=".$pathForBackup);
        //exec("xtrabackup --prepare --target-dir=".$pathForBackup);
    }
}