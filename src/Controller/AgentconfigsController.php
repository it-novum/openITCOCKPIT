<?php
// Copyright (C) <2020>  <it-novum GmbH>
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

use App\Model\Entity\Agentcheck;
use App\Model\Entity\Changelog;
use App\Model\Entity\Host;
use App\Model\Table\AgentchecksTable;
use App\Model\Table\AgentconfigsTable;
use App\Model\Table\AgenthostscacheTable;
use App\Model\Table\ChangelogsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use itnovum\openITCOCKPIT\Agent\AgentResponseToServicetemplateMapper;
use itnovum\openITCOCKPIT\Agent\HttpLoader;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\Comparison\ServiceComparisonForSave;
use itnovum\openITCOCKPIT\Core\UUID;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgentchecksFilter;

/**
 * Class AgentconfigsController
 * @package App\Controller
 */
class AgentconfigsController extends AppController {

    /**
     * @param int|null $hostId
     */
    public function config($hostId = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship template
            return;
        }

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($hostId)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($hostId);
        if (!$this->allowedByContainerId($host->getContainerIds(), true)) {
            $this->render403();
            return;
        }

        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        if ($this->request->is('get')) {
            $config = $AgentconfigsTable->getConfigByHostId($hostId, true);
            $this->set('host', $host);
            $this->set('config', $config);
            $this->viewBuilder()->setOption('serialize', ['host', 'config']);
            return;
        }

        if ($this->request->is('post')) {
            //Save agent configuration
            $entity = $AgentconfigsTable->getConfigOrEmptyEntity($hostId);
            $entity = $AgentconfigsTable->patchEntity($entity, $this->request->getData('Agentconfig'));

            $entity->set('host_id', $hostId);

            $AgentconfigsTable->save($entity);
            if ($entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $entity->getErrors());
                $this->set('success', false);
                $this->viewBuilder()->setOption('serialize', ['error', 'success']);
                return;
            } else {
                $this->set('success', true);
                $this->viewBuilder()->setOption('serialize', ['success']);
                return;
            }

        }
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var AgentconfigsTable $AgentconfigsTable */
            $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');
            $agentconfig = $AgentconfigsTable->newEmptyEntity();
            $agentconfig = $AgentconfigsTable->patchEntity($agentconfig, $this->request->getData('Agentconfig'));

            $AgentconfigsTable->save($agentconfig);
            if ($agentconfig->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $agentconfig->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($agentconfig); // REST API ID serialization
                    return;
                }
            }
            $this->set('agentconfig', $agentconfig);
            $this->viewBuilder()->setOption('serialize', ['agentconfig']);
        }
    }

    /**
     * @param int|null $id
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var AgentconfigsTable $AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        if (!$AgentconfigsTable->existsById($id)) {
            throw new NotFoundException(__('Agentconfig not found'));
        }

        $agentconfig = $AgentconfigsTable->get($id);

        if ($this->request->is('post')) {
            $config = $this->request->getData('Agentconfig');
            if (isset($config['use_https']) && ($config['use_https'] === 'true' || $config['use_https'] === true)) {
                $config['use_https'] = 1;
            } else {
                $config['use_https'] = 0;
            }
            if (isset($config['push_noticed']) && $agentconfig->get('host_id') != 0 && $config['push_noticed'] == 0) {
                /** @var AgenthostscacheTable $AgenthostscacheTable */
                $AgenthostscacheTable = TableRegistry::getTableLocator()->get('Agenthostscache');
                /** @var HostsTable $HostsTable */
                $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

                $hostuuid = $HostsTable->getHostUuidById($agentconfig->get('host_id'));
                if ($AgenthostscacheTable->existsByHostuuid($hostuuid)) {
                    $AgenthostscacheTable->delete($AgenthostscacheTable->getByHostUuid($hostuuid));
                }
            }
            $agentconfig = $AgentconfigsTable->patchEntity($agentconfig, $config);

            $AgentconfigsTable->save($agentconfig);
            if ($agentconfig->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $agentconfig->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($agentconfig); // REST API ID serialization
                    return;
                }
            }
        }
        $this->set('agentconfig', $agentconfig);
        $this->viewBuilder()->setOption('serialize', ['agentconfig']);
    }
}
