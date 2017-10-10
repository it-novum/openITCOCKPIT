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

class AngularController extends AppController {

    public $layout = 'blank';

    public function paginator() {
        //Return HTML Template for PaginatorDirective
        return;
    }

    public function mass_delete(){
        return;
    }

    public function confirm_delete(){
        return;
    }

    public function user_timezone(){
        if(!$this->isApiRequest()){
            //Only ship HTML template
            return;
        }

        $userTimezone = $this->Auth->user('timezone');
        if(strlen($userTimezone) < 2){
            $userTimezone = 'Europe/Berlin';
        }
        $UserTime = new DateTime($userTimezone);
        $ServerTime = new DateTime();

        $timezone = [
            'user_timezone' => $userTimezone,
            'user_offset' => $UserTime->getOffset(),
            'server_time_utc' => time(),
            'server_time' => date('F d, Y H:i:s'),
            'server_timezone_offset' => $ServerTime->getOffset()
        ];
        $this->set('timezone', $timezone);
        $this->set('_serialize', ['timezone']);
    }

    public function version_check(){
        if(!$this->isApiRequest()){
            //Only ship HTML template
            return;
        }

        $path = APP . 'Lib' . DS . 'AvailableVersion.php';
        $availableVersion = '???';
        if (file_exists($path)) {
            require_once $path;
            $availableVersion = openITCOCKPIT_AvailableVersion::get();
        }
        Configure::load('version');
        $newVersionAvailable = false;
        if(version_compare($availableVersion, Configure::read('version')) > 0 && $this->hasRootPrivileges){
            $newVersionAvailable = true;
        }

        $this->set('newVersionAvailable', $newVersionAvailable);
        $this->set('_serialize', ['newVersionAvailable']);
    }

}