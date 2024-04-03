<?php
// Copyright (C) <2024>  <it-novum GmbH>
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

namespace MSTeamsModule\Controller;

use App\Controller\AppController as BaseController;
use Cake\ORM\TableRegistry;
use MSTeamsModule\Model\Table\MsteamsSettingsTable;

class MSTeamsSettingsController extends BaseController {
    public function index(): void {

        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        /** @var MsteamsSettingsTable $MsteamsSettingsTable */
        $MsteamsSettingsTable = TableRegistry::getTableLocator()->get('MSTeamsModule.MsteamsSettings');
        $teamsSettings = $MsteamsSettingsTable->getTeamsSettings();

        if ($this->request->is('get')) {
            $this->set('teamsSettings', $teamsSettings);
            $this->viewBuilder()->setOption('serialize', [
                'teamsSettings'
            ]);
            return;
        }

        if ($this->request->is('post')) {
            $entity = $MsteamsSettingsTable->getTeamsSettingsEntity();
            $entity = $MsteamsSettingsTable->patchEntity($entity, $this->request->getData(null, []));

            $MsteamsSettingsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('teamsSettings', $entity);
            $this->viewBuilder()->setOption('serialize', [
                'teamsSettings'
            ]);
        }
    }
}
