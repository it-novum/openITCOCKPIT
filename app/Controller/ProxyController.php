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


use App\Model\Table\ProxiesTable;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class ProxyController
 */
class ProxyController extends AppController {
    public $layout = 'blank';

    use LocatorAwareTrait;

    function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        $TableLocator = $this->getTableLocator();

        /** @var $ProxiesTable ProxiesTable */
        $ProxiesTable = $TableLocator->get('Proxies');

        if ($this->request->is('post') && $this->isAngularJsRequest()) {
            $entity = $ProxiesTable->find()->first();
            if (is_null($entity)) {
                //No proxy configuration found
                $entity = $ProxiesTable->newEmptyEntity();
            }

            $entity = $ProxiesTable->patchEntity($entity, $this->request->data('Proxy'));

            if ($entity->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $entity->getErrors());
                $this->set('_serialize', ['error']);
                return;
            }

            $ProxiesTable->save($entity);
        }

        $settings = $ProxiesTable->getSettings();
        $this->set('proxy', $settings);
        $this->set('_serialize', ['proxy']);
    }

}
