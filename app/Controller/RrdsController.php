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

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::import('Utility', 'Xml');


class RrdsController extends AppController {

    var $uses = ['Rrd'];

    function index() {

    }

    public function ajax() {
        //Do some AJAX Requesthandling...
        if ($this->request->is('ajax')) {
            if (isset($this->request->data['host_uuid']) && isset($this->request->data['service_uuid'])) {
                $this->set('rrd_data', $this->Rrd->getPerfDataFiles($this->request->data['host_uuid'], $this->request->data['service_uuid']));
                $this->set('_serialize', ['rrd_data']);
            }
        } else {
            $this->redirect('/');
        }
    }
}
