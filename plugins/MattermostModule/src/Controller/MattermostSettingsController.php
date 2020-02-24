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

namespace MattermostModule\Controller;

use Cake\ORM\TableRegistry;
use MattermostModule\Model\Table\MattermostSettingsTable;

/**
 * MattermostSettings Controller
 *
 *
 * @method \MattermostModule\Model\Entity\MattermostSetting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MattermostSettingsController extends AppController {
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index() {

        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        /** @var MattermostSettingsTable $MattermostSettingsTable */
        $MattermostSettingsTable = TableRegistry::getTableLocator()->get('MattermostModule.MattermostSettings');
        $mattermostSettings = $MattermostSettingsTable->getMattermostSettings();

        if ($this->request->is('get')) {
            $this->set('mattermostSettings', $mattermostSettings);
            $this->viewBuilder()->setOption('serialize', [
                'mattermostSettings'
            ]);
            return;
        }

        if ($this->request->is('post')) {
            $entity = $MattermostSettingsTable->getMattermostSettingsEntity();
            $entity = $MattermostSettingsTable->patchEntity($entity, $this->request->getData(null, []));

            $MattermostSettingsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }

            $this->set('mattermostSettings', $entity);
            $this->viewBuilder()->setOption('serialize', [
                'mattermostSettings'
            ]);
        }
    }


}
