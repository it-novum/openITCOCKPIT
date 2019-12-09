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

use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;

class SystemsettingsController extends AppController {

    public $layout = 'blank';

    public function index() {

        if (!$this->isApiRequest()) {
            //Only ship template for AngularJs
            return;
        }

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $all_systemsettings = $Systemsettings->getSettings();

        foreach ($all_systemsettings as $key => $value) {
            foreach ($value as $key2 => $systemsetting) {
                $all_systemsettings[$key][$key2]['exploded'] = explode('.', $systemsetting['key'], 2)[1]; //This parse the PREFIX MONITORIN. or WEBSERVER. or WHATEVER. away
            }
        }

        $this->set(compact(['all_systemsettings']));
        $this->viewBuilder()->setOption('serialize', ['all_systemsettings']);

        if ($this->request->is('post') || $this->request->is('put')) {
            $systemsettingsEntity = $Systemsettings->getSystemsettings(true);
            $normalizedData = [];
            foreach ($this->request->data as $requestData) {
                foreach ($requestData as $data)
                    $normalizedData[] = $data;
            }
            $systemsettingsPatchedEntities = $Systemsettings->patchEntities($systemsettingsEntity, $normalizedData);
            $result = $Systemsettings->saveMany($systemsettingsPatchedEntities);
            Cache::clear('permissions');
            //debug($result);
            if (!$result) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', []);
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
            //Update systemname in session
            $systemsettings = $Systemsettings->findAsArraySection('FRONTEND');
            if (isset($systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME'])) {
                $this->Session->write('FRONTEND.SYSTEMNAME', $systemsettings['FRONTEND']['FRONTEND.SYSTEMNAME']);
            }
            if (isset($systemsettings['FRONTEND']['FRONTEND.EXPORT_RUNNING'])) {
                $this->Session->write('FRONTEND.EXPORT_RUNNING', $systemsettings['FRONTEND']['FRONTEND.EXPORT_RUNNING']);
            }
        }
    }

}
