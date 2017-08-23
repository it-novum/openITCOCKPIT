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

class SystemsettingsController extends AppController
{
    public $layout = 'Admin.default';
    //public $components = ['Bbcode'];
    //public $helpers = ['Bbcode'];

    public function index()
    {
        $all_systemsettings = $this->Systemsetting->findNice();
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Systemsetting->saveAll($this->request->data)) {

                //Update systemname in session
                $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
                if (isset($systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])) {
                    $this->Session->write('FRONTEND.SYSTEMNAME', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME']);
                }
                if (isset($systemsettings['FRONTEND']['FRONTEND.EXPORT_RUNNING'])) {
                    $this->Session->write('FRONTEND.EXPORT_RUNNING', $systemsettings['FRONTEND']['FRONTEND.EXPORT_RUNNING']);
                }


                $this->setFlash(__('Settings saved successfully'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Data could not be saved'), false);
            }
        }

        $this->set(compact(['all_systemsettings']));
        $this->set('_serialize', ['all_systemsettings']);
    }
}
