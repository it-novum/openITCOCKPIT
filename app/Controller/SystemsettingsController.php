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

use Cake\ORM\TableRegistry;

class SystemsettingsController extends AppController {
    public $layout = 'angularjs';

    public function index() {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
       // debug($Systemsettings->getSystemsettings());
        $this->set(compact([]));
        $this->set('_serialize', []);

        if ($this->request->is('post') || $this->request->is('put')) {
            $systemsettingsEntity = $Systemsettings->getSystemsettings(true);
            debug($this->request->data);
            $normalizedData = [];
            foreach ($this->request->data as $requestData){
                foreach ($requestData as $data)
                $normalizedData[] = $data;
            }
            debug($normalizedData);

            $systemsettingsNewEntities = $Systemsettings->patchEntities($systemsettingsEntity,$normalizedData);
            debug($systemsettingsNewEntities);
            $result = $Systemsettings->saveMany($systemsettingsNewEntities);
            debug($result);

            /*
            $Systemsettings->getConnection()->transactional(function() use ($Systemsettings, $systemsettingsNewEntities){
                foreach($systemsettingsNewEntities as $entity){
                    $Systemsettings->save($entity, ['atomic' => false]);
                }
            });
*/

            Cache::clear(false, 'permissions');
      /*       if ($systemsettingsNewEntities->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $Systemsettings->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }
*/
            //Update systemname in session
            $systemsettings = $this->Systemsetting->findAsArraySection('FRONTEND');
            if (isset($systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])) {
                $this->Session->write('FRONTEND.SYSTEMNAME', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME']);
            }
            if (isset($systemsettings['FRONTEND']['FRONTEND.EXPORT_RUNNING'])) {
                $this->Session->write('FRONTEND.EXPORT_RUNNING', $systemsettings['FRONTEND']['FRONTEND.EXPORT_RUNNING']);
            }
        }
    }

    public function getSystemsettings(){
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $all_systemsettings = $Systemsettings->getSettings();

        foreach ($all_systemsettings as $key => $value){
            foreach ($value as $key2 => $systemsetting){
                $all_systemsettings[$key][$key2]['exploded'] = explode('.', $systemsetting['key'], 2)[1]; //This parse the PREFIX MONITORIN. or WEBSERVER. or WHATEVER. away
            }
        }

        $this->set(compact(['all_systemsettings']));
        $this->set('_serialize', ['all_systemsettings']);
    }
}
